<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\User;

class CartService
{
    /**
     * Lấy toàn bộ giỏ hàng của User hiện tại (từ DB hoặc Session)
     */
    public function getCart(): array
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Nếu có session cũ được khách mang vào, gộp ngay vào DB.
            $sessionCart = session()->get('cart', []);
            if (!empty($sessionCart)) {
                $dbCart = is_string($user->cart_data) ? json_decode($user->cart_data, true) : ($user->cart_data ?? []);
                
                // Merge logic (cộng dồn số lượng nếu trùng variant_id)
                foreach ($sessionCart as $variantId => $item) {
                    if (isset($dbCart[$variantId])) {
                        $dbCart[$variantId]['quantity'] += $item['quantity'];
                    } else {
                        $dbCart[$variantId] = $item;
                    }
                }
                
                $user->cart_data = $dbCart;
                $user->save();
                session()->forget('cart'); // Xóa session đi để sau này chỉ đọc từ DB
                return $dbCart;
            }
            
            return is_string($user->cart_data) ? json_decode($user->cart_data, true) : ($user->cart_data ?? []);
        }

        return session()->get('cart', []);
    }

    /**
     * Cập nhật Giỏ hàng.
     * Sử dụng Closure để xử lý dữ liệu và khóa Transaction chống Race Condition
     * @param callable $modifier Hàm chỉnh sửa giỏ hàng (function(&$cart) {})
     */
    public function updateCart(callable $modifier): void
    {
        if (auth()->check()) {
            DB::transaction(function () use ($modifier) {
                // Khóa bản ghi user tranh bị đè bởi tab khác
                $user = User::where('id', auth()->id())->lockForUpdate()->first();
                
                $cart = $user->cart_data ?? [];
                if (is_string($cart)) {
                    $cart = json_decode($cart, true) ?? [];
                }

                // Chạy logic sửa/xóa/thêm từ CartController
                call_user_func_array($modifier, [&$cart]);

                // Lưi lại
                $user->update(['cart_data' => $cart]);
                
                // Đồng bộ lại object Auth trong bộ nhớ để getCart() lấy đúng dữ liệu mới nhất trong cùng 1 request
                auth()->user()->cart_data = $cart;

                // Theo dõi giỏ hàng bỏ quên
                try {
                    app(\App\Services\ConversionTrackingService::class)->trackAbandonment(auth()->id(), session()->getId(), $cart);
                } catch (\Exception $e) {}
            });
        } else {
            // Đối với KHÁCH, tạm lưu qua Session
            $cart = session()->get('cart', []);
            call_user_func_array($modifier, [&$cart]);
            session()->put('cart', $cart);

            // Theo dõi giỏ hàng bỏ quên (cho tracking cookie)
            try {
                app(\App\Services\ConversionTrackingService::class)->trackAbandonment(null, session()->getId(), $cart);
            } catch (\Exception $e) {}
        }
    }

    /**
     * Dùng để override toàn bộ cart (Xóa tất cả)
     */
    public function clearCart(): void
    {
        if (auth()->check()) {
            DB::transaction(function () {
                $user = User::where('id', auth()->id())->lockForUpdate()->first();
                $user->update(['cart_data' => []]);
                auth()->user()->cart_data = [];
            });
        } else {
            session()->forget('cart');
        }
    }

    /**
     * Xóa các items cụ thể ra khỏi giỏ hàng
     */
    public function removeItems(array $variantIds): void
    {
        if (empty($variantIds)) return;

        $this->updateCart(function (&$cart) use ($variantIds) {
            foreach ($variantIds as $vid) {
                if (isset($cart[$vid])) {
                    unset($cart[$vid]);
                }
            }
        });
    }

    /**
     * Khôi phục toàn bộ sản phẩm từ một đơn hàng bị hủy vào lại giỏ hàng
     */
    public function restoreOrderToCart(\App\Models\Order $order): void
    {
        $this->updateCart(function (&$cart) use ($order) {
            foreach ($order->items as $item) {
                $product = $item->product;
                $variant = $item->variant;
                
                if (!$product || !$variant) continue;
                
                $vid = (string)$variant->id;
                
                if (isset($cart[$vid])) {
                    $cart[$vid]['quantity'] += $item->quantity;
                } else {
                    $cart[$vid] = [
                        'product_id' => $product->id,
                        'variant_id' => $variant->id,
                        'name' => $product->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price, 
                        'image' => $product->image,
                        'size' => $variant->sizeRelationship ? $variant->sizeRelationship->name : $variant->size,
                        'color' => $variant->colorRelationship ? $variant->colorRelationship->name : $variant->color,
                        'size_id' => $variant->size_id,
                        'color_id' => $variant->color_id,
                        'slug' => $product->slug,
                    ];
                }
            }
        });
        
        // Kích hoạt event cập nhật số lượng badge icon giỏ hàng
        $cartCount = array_sum(array_column($this->getCart(), 'quantity'));
        \App\Events\CartUpdatedEvent::dispatch($cartCount, session()->getId(), auth()->id());
    }
}
