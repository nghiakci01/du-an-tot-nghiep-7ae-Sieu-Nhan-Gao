<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Shipping\GhnShippingProvider;
use App\Services\Shipping\GhtkShippingProvider;
use App\Services\Shipping\ViettelPostShippingProvider;
use Illuminate\Support\Facades\Log;

class ShippingController extends Controller
{
    public function calculateFees(Request $request)
    {
        try {
            $request->validate([
                'province' => 'required|string',
                'district' => 'required|string',
                'ward' => 'required|string',
                'weight' => 'nullable|integer',
            ]);

            $province = $request->input('province');
            $district = $request->input('district');
            $ward = $request->input('ward');
            $weight = $request->input('weight', 1000); // Mặt định 1kg nếu không tính toán được

            $options = [];

            // Fetch GHN
            $ghnProvider = new GhnShippingProvider();
            if ($ghnResult = $ghnProvider->calculateFee($province, $district, $ward, $weight)) {
                $options[] = $ghnResult;
            }

            // Fetch GHTK
            $ghtkProvider = new GhtkShippingProvider();
            if ($ghtkResult = $ghtkProvider->calculateFee($province, $district, $ward, $weight)) {
                $options[] = $ghtkResult;
            }

            // Fetch Viettel Post
            $viettelProvider = new ViettelPostShippingProvider();
            if ($viettelResult = $viettelProvider->calculateFee($province, $district, $ward, $weight)) {
                $options[] = $viettelResult;
            }

            // Nếu tất cả API đều lỗi (hoặc timeout), fallback về một cấu hình cơ bản cứng để không làm kẹt luồng mua hàng
            if (empty($options)) {
                 $options[] = [
                    'provider' => 'flat_rate',
                    'service_name' => 'Giao hàng tiêu chuẩn',
                    'fee' => 30000,
                    'expected_delivery_time' => now()->addDays(3)->format('d/m/Y'),
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $options
            ]);

        } catch (\Exception $e) {
            Log::error('ShippingController calculateFees error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tính phí vận chuyển',
                'data' => [
                    [
                        'provider' => 'flat_rate',
                        'service_name' => 'Giao hàng tiêu chuẩn (Dự phòng)',
                        'fee' => 30000,
                        'expected_delivery_time' => now()->addDays(3)->format('d/m/Y'),
                    ]
                ]
            ]);
        }
    }
}
