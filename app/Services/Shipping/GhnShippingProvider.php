<?php

namespace App\Services\Shipping;

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

        $provinces = Cache::remember('shipping.ghn.provinces', 86400, function () use ($config) {
            $response = Http::timeout(8)
                ->acceptJson()
                ->withHeaders([
                    'Token' => $config['token'] ?? '',
                ])
                ->get(rtrim($config['api_url'], '/') . '/shiip/public-api/v2/master-data/province');

            if (!$response->successful()) {
                Log::warning('GHN province lookup failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [];
            }

            return data_get($response->json(), 'data', []);
        });

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
        return Cache::remember('shipping.ghn.districts.' . $provinceId, 86400, function () use ($provinceId, $config) {
            $response = Http::timeout(8)
                ->acceptJson()
                ->withHeaders([
                    'Token' => $config['token'] ?? '',
                ])
                ->get(rtrim($config['api_url'], '/') . '/shiip/public-api/v2/master-data/district', [
                    'province_id' => $provinceId,
                ]);

            if (!$response->successful()) {
                Log::warning('GHN district lookup failed', [
                    'province_id' => $provinceId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [];
            }

            return data_get($response->json(), 'data', []);
        });
    }

    protected function getWardsByDistrictId(int $districtId, array $config): array
    {
        return Cache::remember('shipping.ghn.wards.' . $districtId, 86400, function () use ($districtId, $config) {
            $response = Http::timeout(8)
                ->acceptJson()
                ->withHeaders([
                    'Token' => $config['token'] ?? '',
                ])
                ->get(rtrim($config['api_url'], '/') . '/shiip/public-api/v2/master-data/ward', [
                    'district_id' => $districtId,
                ]);

            if (!$response->successful()) {
                Log::warning('GHN ward lookup failed', [
                    'district_id' => $districtId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [];
            }

            return data_get($response->json(), 'data', []);
        });
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
}
