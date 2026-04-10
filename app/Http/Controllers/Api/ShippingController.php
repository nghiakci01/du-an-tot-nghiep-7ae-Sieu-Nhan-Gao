<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\Shipping\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingController extends Controller
{
    public function __construct(
        protected ShippingService $shippingService,
        protected CartService $cartService
    ) {
    }

    public function calculateFees(Request $request)
    {
        try {
            $deliveryType = $request->input('delivery_type');
            if (!in_array($deliveryType, ['home', 'store'], true)) {
                $deliveryType = 'home';
            }

            $request->validate([
                'delivery_type' => 'nullable|in:home,store',
                'province' => $deliveryType === 'store' ? 'nullable|string' : 'required|string',
                'district' => 'nullable|string',
                'commune' => 'nullable|string',
                'ward' => 'nullable|string',
                'weight' => 'nullable|integer',
            ]);

            $province = $request->input('province');
            $district = $request->input('district');
            $ward = $request->filled('ward') ? $request->input('ward') : $request->input('commune');

            $cart = $this->getSelectedCheckoutCart();
            $subtotal = collect($cart)->sum(fn ($item) => (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0));
            $discount = (float) session('discount_amount', 0);
            $weight = (int) $request->input('weight', $this->shippingService->estimateWeightFromCart($cart));

            $options = $this->shippingService->getOptions(
                $deliveryType,
                $province,
                $district,
                $ward,
                $weight,
                max(0, $subtotal - $discount)
            );

            return response()->json([
                'success' => true,
                'data' => $options,
            ]);
        } catch (\Exception $e) {
            Log::error('ShippingController calculateFees error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Loi tinh phi van chuyen',
                'data' => [
                    [
                        'provider' => 'flat_rate',
                        'service_name' => 'Giao hang tieu chuan (Du phong)',
                        'fee' => 30000,
                        'expected_delivery_time' => now()->addDays(3)->format('d/m/Y'),
                    ],
                ],
            ]);
        }
    }

    protected function getSelectedCheckoutCart(): array
    {
        $cart = $this->cartService->getCart();
        $selectedIds = session('selected_checkout_ids');

        if (!$selectedIds || !is_array($selectedIds)) {
            return $cart;
        }

        $selectedIds = array_map('strval', $selectedIds);

        return array_filter($cart, function ($key) use ($selectedIds) {
            return in_array((string) $key, $selectedIds, true);
        }, ARRAY_FILTER_USE_KEY);
    }
}
