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

                // Lưu lại
                $user->update(['cart_data' => $cart]);
            });
        } else {
            // Đối với KHÁCH, tạm lưu qua Session
            $cart = session()->get('cart', []);
            call_user_func_array($modifier, [&$cart]);
            session()->put('cart', $cart);
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
}
