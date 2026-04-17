<?php

namespace App\Services\Shipping;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GhnShippingProvider implements ShippingProviderInterface
{
    public function calculateFee(string $toProvince, string $toDistrict, string $toWard, int $weight): ?array
    {
        try {
            $config = config('shipping.ghn', []);

            if ($this->canUseLiveApi($config)) {
                $destination = $this->resolveDestination($toProvince, $toDistrict, $toWard, $config);

                if ($destination !== null) {
                    $quote = $this->requestLiveQuote($config, $destination, $weight);

                    if ($quote !== null) {
                        return $quote;
                    }
                }
            }

            return $this->buildMockQuote($toProvince, $weight);
        } catch (\Throwable $e) {
            Log::error('GHN Shipping Error: ' . $e->getMessage(), ['exception' => $e]);
            return null;
        }
    }

    protected function canUseLiveApi(array $config): bool
    {
        return (bool) ($config['enabled'] ?? false)
            && filled($config['token'] ?? null)
            && filled($config['shop_id'] ?? null);
    }

    protected function resolveDestination(string $toProvince, string $toDistrict, string $toWard, array $config): ?array
    {
        $provinceId = $this->findProvinceId($toProvince, $config);

        if ($provinceId === null) {
            return null;
        }

        $normalizedDistrict = trim($toDistrict);
        $normalizedWard = trim($toWard);

        if ($normalizedDistrict !== '' && $normalizedWard !== '') {
            $district = $this->findDistrictByName($provinceId, $normalizedDistrict, $config);

            if ($district !== null) {
                $ward = $this->findWardByName((int) $district['DistrictID'], $normalizedWard, $config);

                if ($ward !== null) {
                    return [
                        'province_id' => $provinceId,
                        'district_id' => (int) $district['DistrictID'],
                        'ward_code' => (string) $ward['WardCode'],
                    ];
                }
            }
        }

        if ($normalizedWard === '') {
            return null;
        }

        foreach ($this->getDistrictsByProvinceId($provinceId, $config) as $district) {
            $ward = $this->findWardByName((int) $district['DistrictID'], $normalizedWard, $config);

            if ($ward !== null) {
                return [
                    'province_id' => $provinceId,
                    'district_id' => (int) $district['DistrictID'],
                    'ward_code' => (string) $ward['WardCode'],
                ];
            }
        }

        return null;
    }

    protected function requestLiveQuote(array $config, array $destination, int $weight): ?array
    {
        $payload = [
            'service_type_id' => (int) ($config['service_type_id'] ?? 2),
            'to_district_id' => $destination['district_id'],
            'to_ward_code' => $destination['ward_code'],
            'weight' => max(1, $weight),
            'length' => 30,
            'width' => 20,
            'height' => 10,
            'insurance_value' => 0,
            'coupon' => null,
        ];

        if (filled($config['from_district_id'] ?? null)) {
            $payload['from_district_id'] = (int) $config['from_district_id'];
        }

        if (filled($config['from_ward_code'] ?? null)) {
            $payload['from_ward_code'] = (string) $config['from_ward_code'];
        }

        $response = Http::timeout(8)
            ->acceptJson()
            ->withHeaders(array_filter([
                'Token' => $config['token'] ?? null,
                'ShopId' => $config['shop_id'] ?? null,
            ]))
            ->get(rtrim($config['api_url'], '/') . '/shiip/public-api/v2/shipping-order/fee', $payload);

        if (!$response->successful()) {
            Log::warning('GHN live quote failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        $responsePayload = $response->json();
        $fee = data_get($responsePayload, 'data.total_fee')
            ?? data_get($responsePayload, 'data.total')
            ?? data_get($responsePayload, 'data.service_fee');

        if (!is_numeric($fee)) {
            Log::warning('GHN live quote returned unexpected payload', ['payload' => $responsePayload]);

            return null;
        }

        return [
            'provider' => 'ghn',
            'service_name' => 'Giao Hang Nhanh',
            'fee' => (int) round($fee),
            'expected_delivery_time' => $this->formatExpectedDeliveryTime(
                data_get($responsePayload, 'data.expected_delivery_time')
                    ?? data_get($responsePayload, 'data.leadtime')
            ),
        ];
    }

    protected function formatExpectedDeliveryTime(mixed $value): string
    {
        if (is_numeric($value)) {
            return Carbon::createFromTimestamp((int) $value)->format('d/m/Y');
        }

        if (is_string($value) && filled($value)) {
            try {
                return Carbon::parse($value)->format('d/m/Y');
            } catch (\Throwable) {
                // Fall through to the default estimate below.
            }
        }

        return now()->addDays(2)->format('d/m/Y');
    }

    protected function findProvinceId(string $provinceName, array $config): ?int
    {
        $needle = $this->normalize($provinceName);

        if ($needle === '') {
            return null;
        }

        $provinces = Cache::get('shipping.ghn.provinces');

        if (!is_array($provinces) || empty($provinces)) {
            $response = Http::timeout(8)
                ->acceptJson()
                ->withHeaders([
                    'Token' => $config['token'] ?? '',
                ])
                ->get(rtrim($config['api_url'], '/') . '/shiip/public-api/master-data/province');

            if (!$response->successful()) {
                if ($response->status() === 401 || $response->status() === 403) {
                    throw new \Exception('Lỗi kết nối GHN: Token không hợp lệ hoặc đã hết hạn. Vui lòng kiểm tra GHN_TOKEN trong .env.');
                }
                Log::warning('GHN province lookup failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $provinces = data_get($response->json(), 'data', []);
            
            if (is_array($provinces) && !empty($provinces)) {
                Cache::put('shipping.ghn.provinces', $provinces, 86400);
            }
        }

        foreach ($provinces as $province) {
            $names = array_filter([
                data_get($province, 'ProvinceName'),
                data_get($province, 'province_name'),
                data_get($province, 'NameExtension'),
            ]);

            if ($this->matchesAnyName($needle, $names)) {
                $provinceId = data_get($province, 'ProvinceID') ?? data_get($province, 'province_id');

                return is_numeric($provinceId) ? (int) $provinceId : null;
            }
        }

        return null;
    }

    protected function findDistrictByName(int $provinceId, string $districtName, array $config): ?array
    {
        $needle = $this->normalize($districtName);

        if ($needle === '') {
            return null;
        }

        foreach ($this->getDistrictsByProvinceId($provinceId, $config) as $district) {
            $names = array_filter([
                data_get($district, 'DistrictName'),
                data_get($district, 'district_name'),
                data_get($district, 'NameExtension'),
            ]);

            if ($this->matchesAnyName($needle, $names)) {
                return $district;
            }
        }

        return null;
    }

    protected function findWardByName(int $districtId, string $wardName, array $config): ?array
    {
        $needle = $this->normalize($wardName);

        if ($needle === '') {
            return null;
        }

        foreach ($this->getWardsByDistrictId($districtId, $config) as $ward) {
            $names = array_filter([
                data_get($ward, 'WardName'),
                data_get($ward, 'ward_name'),
                data_get($ward, 'NameExtension'),
            ]);

            if ($this->matchesAnyName($needle, $names)) {
                return $ward;
            }
        }

        return null;
    }

    protected function getDistrictsByProvinceId(int $provinceId, array $config): array
    {
        $cacheKey = 'shipping.ghn.districts.' . $provinceId;
        $districts = Cache::get($cacheKey);

        if (!is_array($districts) || empty($districts)) {
            $response = Http::timeout(8)
                ->acceptJson()
                ->withHeaders([
                    'Token' => $config['token'] ?? '',
                ])
                ->get(rtrim($config['api_url'], '/') . '/shiip/public-api/master-data/district', [
                    'province_id' => $provinceId,
                ]);

            if (!$response->successful()) {
                if ($response->status() === 401 || $response->status() === 403) {
                    throw new \Exception('Lỗi kết nối GHN (Quận/Huyện): Token không hợp lệ.');
                }
                Log::warning('GHN district lookup failed', [
                    'province_id' => $provinceId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [];
            }

            $districts = data_get($response->json(), 'data', []);

            if (is_array($districts) && !empty($districts)) {
                Cache::put($cacheKey, $districts, 86400);
            }
        }

        return $districts ?: [];
    }

    protected function getWardsByDistrictId(int $districtId, array $config): array
    {
        $cacheKey = 'shipping.ghn.wards.' . $districtId;
        $wards = Cache::get($cacheKey);

        if (!is_array($wards) || empty($wards)) {
            $response = Http::timeout(8)
                ->acceptJson()
                ->withHeaders([
                    'Token' => $config['token'] ?? '',
                ])
                ->get(rtrim($config['api_url'], '/') . '/shiip/public-api/master-data/ward', [
                    'district_id' => $districtId,
                ]);

            if (!$response->successful()) {
                if ($response->status() === 401 || $response->status() === 403) {
                    throw new \Exception('Lỗi kết nối GHN (Phường/Xã): Token không hợp lệ.');
                }
                Log::warning('GHN ward lookup failed', [
                    'district_id' => $districtId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [];
            }

            $wards = data_get($response->json(), 'data', []);

            if (is_array($wards) && !empty($wards)) {
                Cache::put($cacheKey, $wards, 86400);
            }
        }

        return $wards ?: [];
    }

    protected function matchesAnyName(string $needle, array $names): bool
    {
        foreach ($names as $name) {
            foreach ((array) $name as $candidate) {
                if (!is_string($candidate) && !is_numeric($candidate)) {
                    continue;
                }

                $normalizedCandidate = $this->normalize((string) $candidate);

                if (
                    $normalizedCandidate === $needle
                    || str_contains($normalizedCandidate, $needle)
                    || str_contains($needle, $normalizedCandidate)
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function normalize(string $value): string
    {
        // Heuristic to detect common UTF-8 interpreted as ISO-8859-1 (Mojibake)
        // Example: "PhÃº Thá»" -> "Phú Thọ"
        if (str_contains($value, 'Ã') || str_contains($value, 'áº')) {
            $healed = @mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
            // If the healed string looks like UTF-8 and is different, we use it
            if ($healed && $healed !== $value && mb_check_encoding($healed, 'UTF-8')) {
                $value = $healed;
            }
        }

        return Str::lower(trim(Str::ascii($value)));
    }

    protected function buildMockQuote(string $toProvince, int $weight): array
    {
        usleep(200000);

        $baseFee = 30000;
        $normalizedProvince = $this->normalize($toProvince);

        if (str_contains($normalizedProvince, 'ho chi minh') || str_contains($normalizedProvince, 'ha noi')) {
            $baseFee = 22000;
        }

        if ($weight > 1000) {
            $extraWeight = ceil(($weight - 1000) / 1000);
            $baseFee += $extraWeight * 5000;
        }

        return [
            'provider' => 'ghn',
            'service_name' => 'Giao Hang Nhanh',
            'fee' => $baseFee,
            'expected_delivery_time' => now()->addDays(2)->format('d/m/Y'),
        ];
    }

    public function createShippingOrder(\App\Models\Order $order): array
    {
        $config = config('shipping.ghn', []);

        if (!$this->canUseLiveApi($config)) {
            Log::error('GHN Create Order failed: API not configured or disabled in config/shipping.php');
            throw new \Exception('Chưa cấu hình API GHN hoặc chưa bật (enabled).');
        }

        // Parse address to get province, district, ward
        // Format of shipping_address: "Address, Ward, District, Province - Phone - Name"
        $addressFull = $order->shipping_address;
        $addressPart = explode(' - ', $addressFull)[0] ?? '';
        $parts = array_map('trim', explode(',', $addressPart));
        $normalizedParts = array_map([$this, 'normalize'], $parts);

        Log::debug('GHN Heuristic Parsing start', ['address' => $addressPart, 'parts' => $parts]);

        // 1. Identify Province Candidates
        // We fetch all provinces (cached) and check which one matches any part of the address
        $allProvinces = Cache::get('shipping.ghn.provinces');
        if (!is_array($allProvinces) || empty($allProvinces)) {
            // Trigger a dummy lookup to load cache if empty
            $this->findProvinceId('Hanoi', $config);
            $allProvinces = Cache::get('shipping.ghn.provinces') ?: [];
        }

        $provinceCandidates = [];
        
        // Priority: Check order->province first
        if ($order->province) {
            $pId = $this->findProvinceId($order->province, $config);
            if ($pId) {
                $provinceCandidates[$pId] = [
                    'id' => $pId,
                    'name' => $order->province,
                    'score' => 100 // High base score for explicit field
                ];
            }
        }

        // Scan address parts for other province mentions
        foreach ($allProvinces as $province) {
            $pId = data_get($province, 'ProvinceID') ?? data_get($province, 'province_id');
            if (!$pId) continue;
            
            $pNames = array_filter([
                data_get($province, 'ProvinceName'),
                data_get($province, 'province_name'),
                data_get($province, 'NameExtension'),
            ]);

            foreach ($normalizedParts as $nPart) {
                if ($this->matchesAnyName($nPart, $pNames)) {
                    $pIdInt = (int)$pId;
                    if (!isset($provinceCandidates[$pIdInt])) {
                        $provinceCandidates[$pIdInt] = [
                            'id' => $pIdInt,
                            'name' => (string)data_get($province, 'ProvinceName'),
                            'score' => 40
                        ];
                    } else {
                        $provinceCandidates[$pIdInt]['score'] += 20;
                    }
                }
            }
        }

        if (empty($provinceCandidates)) {
            throw new \Exception("Không thể nhận diện Tỉnh/Thành phố từ địa chỉ: $addressPart. Vui lòng kiểm tra lại thông tin Tỉnh/Thành.");
        }

        // 2. Explore Paths and Score Them
        $bestPath = null;
        $maxScore = -1;

        // Sort candidates by score descending to find best province first
        uasort($provinceCandidates, fn($a, $b) => $b['score'] <=> $a['score']);
        
        // Limit to top 3 candidates to prevent excessive API calls
        $topCandidates = array_slice($provinceCandidates, 0, 3, true);

        foreach ($topCandidates as $pId => $pData) {
            $districts = $this->getDistrictsByProvinceId($pId, $config);
            
            foreach ($districts as $district) {
                $dId = (int)data_get($district, 'DistrictID');
                $dNames = array_filter([
                    data_get($district, 'DistrictName'),
                    data_get($district, 'NameExtension'),
                ]);

                $dMatchScore = 0;
                foreach ($normalizedParts as $nPart) {
                    if ($this->matchesAnyName($nPart, $dNames)) {
                        $dMatchScore = 50; // Found District match
                        break;
                    }
                }

                // Optimization: If no district match, we still try because Ward might match
                $wards = $this->getWardsByDistrictId($dId, $config);
                foreach ($wards as $ward) {
                    $wCode = (string)data_get($ward, 'WardCode');
                    $wNames = array_filter([
                        data_get($ward, 'WardName'),
                        data_get($ward, 'NameExtension'),
                    ]);

                    $wMatchScore = 0;
                    foreach ($normalizedParts as $nPart) {
                        if ($this->matchesAnyName($nPart, $wNames)) {
                            $wMatchScore = 150; // Found Ward match (High Priority)
                            break;
                        }
                    }

                    if ($dMatchScore > 0 || $wMatchScore > 0) {
                        $totalScore = $pData['score'] + $dMatchScore + $wMatchScore;
                        
                        if ($totalScore > $maxScore) {
                            $maxScore = $totalScore;
                            $bestPath = [
                                'province_id' => $pId,
                                'district_id' => $dId,
                                'ward_code' => $wCode,
                                'province_name' => $pData['name'],
                                'district_name' => data_get($district, 'DistrictName'),
                                'ward_name' => data_get($ward, 'WardName'),
                                'score' => $totalScore
                            ];
                        }
                    }
                }
            }
            
            // If we found a very strong match (>250), we can stop early
            if ($maxScore >= 250) break;
        }

        if (!$bestPath) {
            // Final attempt: Search for ward globally in all candidate provinces (ignoring district part hierarchy)
            Log::debug('GHN Heuristic falling back to global ward search');
            // ... (Logic already covered by the loops above effectively) ...
            
            throw new \Exception("Không thể nhận diện Phường/Xã từ địa chỉ: $addressPart. Vui lòng đảm bảo thông tin Quận/Huyện và Phường/Xã khớp với Tỉnh/Thành.");
        }

        Log::info('GHN Address resolved using heuristic algorithm', $bestPath);

        $provinceId = $bestPath['province_id'];
        $districtId = $bestPath['district_id'];
        $wardCode = $bestPath['ward_code'];
        $provinceNameFound = $bestPath['province_name'];
        $districtNameFound = $bestPath['district_name'];
        $wardNameFound = $bestPath['ward_name'];

        $weight = 500;
        $totalQuantity = $order->items->sum('quantity');
        if ($totalQuantity > 0) {
             $weight = max(500, 200 + ($totalQuantity * 300));
        }
        
        $items = [];
        foreach ($order->items as $item) {
             $items[] = [
                 'name' => $item->product->name ?? 'Sản phẩm',
                 'quantity' => (int) $item->quantity,
                 'price' => (int) $item->price,
                 'weight' => 300,
             ];
        }

        $codAmount = ($order->payment_method === 'COD' && $order->payment_status === 'pending') ? $order->final_total : 0;

        $payload = [
            'payment_type_id' => 1, // 1: Shop trả phí, 2: Khách trả
            'required_note' => 'CHOXEMHANGKHONGTHU',
            'to_name' => $order->name,
            'to_phone' => $order->phone,
            'to_address' => $addressPart,
            'to_ward_code' => $wardCode,
            'to_district_id' => $districtId,
            'cod_amount' => (int) $codAmount,
            'insurance_value' => (int) $order->final_total, // Recommended to avoid COD_IS_OVER_LIMIT
            'weight' => $weight,
            'length' => 20,
            'width' => 20,
            'height' => 10,
            'service_type_id' => (int) ($config['service_type_id'] ?? 2),
            'items' => $items,
            'client_order_code' => (string) $order->id,
        ];
        
        $response = Http::timeout(10)
            ->acceptJson()
            ->withHeaders(array_filter([
                'Token' => $config['token'] ?? null,
                'ShopId' => (int) ($config['shop_id'] ?? 0),
            ]))
            ->post(rtrim($config['api_url'], '/') . '/shiip/public-api/v2/shipping-order/create', $payload);

        if (!$response->successful()) {
            throw new \Exception('Lỗi từ GHN: ' . $response->body());
        }

        $data = $response->json();
        $orderCode = data_get($data, 'data.order_code');
        
        if (!$orderCode) {
            throw new \Exception('GHN API thành công nhưng không trả về mã vận đơn.');
        }

        $order->update(['tracking_code' => $orderCode]);
        
        return $data;
    }

    /**
     * Hủy đơn hàng trên hệ thống GHN
     */
    public function cancelShippingOrder(string $trackingCode): bool
    {
        $config = config('shipping.ghn', []);

        if (!$this->canUseLiveApi($config)) {
            Log::warning("GHN Cancellation skipped: API not configured or disabled.", ['tracking_code' => $trackingCode]);
            return false;
        }

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->withHeaders(array_filter([
                    'Token' => $config['token'] ?? null,
                    'ShopId' => $config['shop_id'] ?? null,
                ]))
                ->post(rtrim($config['api_url'], '/') . '/shiip/public-api/v2/shipping-order/cancel', [
                    'order_codes' => [$trackingCode]
                ]);

            if (!$response->successful()) {
                Log::error('GHN Cancel Order failed', [
                    'tracking_code' => $trackingCode,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }

            Log::info("GHN Order cancelled successfully: $trackingCode");
            return true;
        } catch (\Exception $e) {
            Log::error('GHN Cancel Order error: ' . $e->getMessage(), ['tracking_code' => $trackingCode]);
            return false;
        }
    }
}
