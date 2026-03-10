<?php

namespace App\Services\Shipping;

use Illuminate\Support\Facades\Log;

class ViettelPostShippingProvider implements ShippingProviderInterface
{
    public function calculateFee(string $toProvince, string $toDistrict, string $toWard, int $weight): ?array
    {
        try {
            // TODO: Implement actual API call using env('VIETTELPOST_TOKEN'), env('VIETTELPOST_API_URL')
            usleep(300000);
            
            $baseFee = 38000;
            if (mb_stripos($toProvince, 'Hồ Chí Minh') !== false || mb_stripos($toProvince, 'Hà Nội') !== false) {
                $baseFee = 32000;
            }
            
            if ($weight > 1000) {
                $extraWeight = ceil(($weight - 1000) / 1000);
                $baseFee += $extraWeight * 8000;
            }

            return [
                'provider' => 'viettelpost',
                'service_name' => 'Viettel Post (Hỏa Tốc)',
                'fee' => $baseFee,
                'expected_delivery_time' => now()->addDays(1)->format('d/m/Y'),
            ];
        } catch (\Exception $e) {
            Log::error('Viettel Post Shipping Error: ' . $e->getMessage());
            return null;
        }
    }
}
