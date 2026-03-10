<?php

namespace App\Services\Shipping;

use Illuminate\Support\Facades\Log;

class GhtkShippingProvider implements ShippingProviderInterface
{
    public function calculateFee(string $toProvince, string $toDistrict, string $toWard, int $weight): ?array
    {
        try {
            // TODO: Implement actual API call using env('GHTK_TOKEN'), env('GHTK_API_URL')
            usleep(250000);
            
            $baseFee = 28000;
            if (mb_stripos($toProvince, 'Hồ Chí Minh') !== false || mb_stripos($toProvince, 'Hà Nội') !== false) {
                $baseFee = 18000;
            }
            
            if ($weight > 1000) {
                $extraWeight = ceil(($weight - 1000) / 1000);
                $baseFee += $extraWeight * 4500;
            }

            return [
                'provider' => 'ghtk',
                'service_name' => 'Giao Hàng Tiết Kiệm',
                'fee' => $baseFee,
                'expected_delivery_time' => now()->addDays(3)->format('d/m/Y'),
            ];
        } catch (\Exception $e) {
            Log::error('GHTK Shipping Error: ' . $e->getMessage());
            return null;
        }
    }
}
