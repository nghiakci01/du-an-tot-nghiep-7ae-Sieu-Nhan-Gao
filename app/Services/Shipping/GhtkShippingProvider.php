<?php

namespace App\Services\Shipping;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GhtkShippingProvider implements ShippingProviderInterface
{
    public function calculateFee(string $toProvince, string $toDistrict, string $toWard, int $weight): ?array
    {
        try {
            $config = config('shipping.ghtk');

            if ($this->canUseLiveApi($config)) {
                $liveQuote = $this->requestLiveQuote($config, $toProvince, $toDistrict, $weight);

                if ($liveQuote !== null) {
                    return $liveQuote;
                }
            }

            return $this->buildMockQuote($toProvince, $weight);
        } catch (\Exception $e) {
            Log::error('GHTK Shipping Error: ' . $e->getMessage());
            return $this->buildMockQuote($toProvince, $weight);
        }
    }

    protected function canUseLiveApi(array $config): bool
    {
        return (bool) ($config['enabled'] ?? false)
            && filled($config['token'] ?? null)
            && filled(config('shipping.pickup.province'))
            && filled(config('shipping.pickup.district'));
    }

    protected function requestLiveQuote(array $config, string $toProvince, string $toDistrict, int $weight): ?array
    {
        $response = Http::timeout(5)
            ->acceptJson()
            ->withHeaders(array_filter([
                'Token' => $config['token'] ?? null,
                'X-Client-Source' => $config['client_source'] ?? null,
            ]))
            ->get(rtrim($config['api_url'], '/') . '/services/shipment/fee', [
                'address' => config('shipping.pickup.address', 'Kho Elite'),
                'province' => config('shipping.pickup.province'),
                'district' => config('shipping.pickup.district'),
                'pick_province' => config('shipping.pickup.province'),
                'pick_district' => config('shipping.pickup.district'),
                'province_dest' => $toProvince,
                'district_dest' => $toDistrict,
                'weight' => max(1, (int) ceil($weight / 1000)),
                'transport' => $config['transport'] ?? 'road',
            ]);

        if (!$response->successful()) {
            Log::warning('GHTK live quote failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        $payload = $response->json();
        $fee = data_get($payload, 'fee.fee');

        if (!is_numeric($fee)) {
            Log::warning('GHTK live quote returned unexpected payload', ['payload' => $payload]);

            return null;
        }

        return [
            'provider' => 'ghtk',
            'service_name' => 'Giao Hang Tiet Kiem',
            'fee' => (int) round($fee),
            'expected_delivery_time' => now()->addDays(3)->format('d/m/Y'),
        ];
    }

    protected function buildMockQuote(string $toProvince, int $weight): array
    {
        usleep(250000);

        $baseFee = 28000;
        $normalizedProvince = Str::lower(Str::ascii($toProvince));
        if (str_contains($normalizedProvince, 'ho chi minh') || str_contains($normalizedProvince, 'ha noi')) {
            $baseFee = 18000;
        }

        if ($weight > 1000) {
            $extraWeight = ceil(($weight - 1000) / 1000);
            $baseFee += $extraWeight * 4500;
        }

        return [
            'provider' => 'ghtk',
            'service_name' => 'Giao Hang Tiet Kiem',
            'fee' => $baseFee,
            'expected_delivery_time' => now()->addDays(3)->format('d/m/Y'),
        ];
    }
}
