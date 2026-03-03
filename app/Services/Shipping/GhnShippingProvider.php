<?php

namespace App\Services\Shipping;

use Illuminate\Support\Facades\Log;

class GhnShippingProvider implements ShippingProviderInterface
{
    public function calculateFee(string $toProvince, string $toDistrict, string $toWard, int $weight): ?array
    {
        try {
            // TODO: Implement actual API call using env('GHN_TOKEN'), env('GHN_API_URL')
            // Dưới đây là Mock Data (Dữ liệu giả lập) để hệ thống có thể chạy ngay
            usleep(200000); // Giả lập độ trễ mạng
            
            // Xử lý logic tính phí dựa trên Tỉnh/Thành
            $baseFee = 30000;
            if (mb_stripos($toProvince, 'Hồ Chí Minh') !== false || mb_stripos($toProvince, 'Hà Nội') !== false) {
                $baseFee = 22000;
            }
            
            // Phụ phí cân nặng: cứ mỗi kg vượt quá 1kg cộng thêm 5000đ
            if ($weight > 1000) {
                $extraWeight = ceil(($weight - 1000) / 1000);
                $baseFee += $extraWeight * 5000;
            }

            return [
                'provider' => 'ghn',
                'service_name' => 'Giao Hàng Nhanh (Chuẩn)',
                'fee' => $baseFee,
                'expected_delivery_time' => now()->addDays(2)->format('d/m/Y'),
            ];
        } catch (\Exception $e) {
            Log::error('GHN Shipping Error: ' . $e->getMessage());
            return null;
        }
    }
}
