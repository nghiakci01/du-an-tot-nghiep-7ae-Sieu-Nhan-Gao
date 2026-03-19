<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Services\CartService;

class InventoryCheckController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Kiểm tra tồn kho cho danh sách sản phẩm gửi từ frontend
     */
    public function checkInventory(Request $request)
    {
        $items = $request->input('items', []);
        
        if (empty($items)) {
            $cart = $this->cartService->getCart();
            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng của bạn đang trống.'
                ], 400);
            }

            // Lọc theo các item đã chọn trong session (giống CheckoutController)
            $selectedIds = session('selected_checkout_ids');
            if ($selectedIds && is_array($selectedIds)) {
                $selectedIds = array_map('strval', $selectedIds);
                $cart = array_filter($cart, function($key) use ($selectedIds) {
                    return in_array(strval($key), $selectedIds);
                }, ARRAY_FILTER_USE_KEY);
            }

            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng chọn sản phẩm trong giỏ hàng trước khi thanh toán.'
                ], 400);
            }

            $items = $cart;
        }

        $errors = [];
        foreach ($items as $key => $item) {
            $variantId = $item['variant_id'] ?? $key;
            $quantity = $item['quantity'] ?? 0;
            
            $variant = ProductVariant::with('product')->find($variantId);

            if (!$variant || !$variant->product) {
                $errors[] = [
                    'variant_id' => $variantId,
                    'name' => $item['name'] ?? 'Sản phẩm không rõ',
                    'available' => 0,
                    'message' => 'Sản phẩm này không còn tồn tại trong hệ thống.'
                ];
                continue;
            }

            if (!$variant->product->is_active) {
                $errors[] = [
                    'variant_id' => $variantId,
                    'name' => $item['name'],
                    'available' => 0,
                    'message' => 'Sản phẩm này đã ngừng kinh doanh.'
                ];
                continue;
            }

            if ($variant->stock_quantity <= 0) {
                $errors[] = [
                    'variant_id' => $variantId,
                    'name' => $item['name'],
                    'available' => 0,
                    'message' => 'Sản phẩm này đã hết hàng.'
                ];
                continue;
            }

            if ($quantity > $variant->stock_quantity) {
                $errors[] = [
                    'variant_id' => $variantId,
                    'name' => $item['name'],
                    'available' => $variant->stock_quantity,
                    'message' => "Chỉ còn {$variant->stock_quantity} sản phẩm trong kho (bạn chọn {$quantity})."
                ];
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'errors' => $errors
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tất cả sản phẩm đều đủ hàng.'
        ]);
    }
}
