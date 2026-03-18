<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;

use App\Services\CartService;

class CheckoutController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Validate tồn kho trước khi vào trang checkout
        $invalidItems = [];
        foreach ($cart as $variantId => $item) {
            $variant = ProductVariant::find($variantId);
            if (!$variant || !$variant->product) {
                $invalidItems[] = '“' . ($item['name'] ?? 'Sản phẩm') . '” đã không còn tồn tại.';
                continue;
            }
            if ($variant->stock_quantity <= 0) {
                $invalidItems[] = '“' . $item['name'] . '” (đã hết hàng).';
                continue;
            }
            if ($item['quantity'] > $variant->stock_quantity) {
                $invalidItems[] = '“' . $item['name'] . '” - chỉ còn ' . $variant->stock_quantity . ' sản phẩm.';
            }
        }

        if (!empty($invalidItems)) {
            $msg = 'Giỏ hàng có sản phẩm không hợp lệ: ' . implode(' ', $invalidItems) . ' Vui lòng cập nhật giỏ hàng trước khi thanh toán.';
            return redirect()->route('cart.index')->with('error', $msg);
        }

        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        // Get applied coupon from session
        $couponCode = session()->get('coupon_code');
        $discount = session()->get('discount_amount', 0);
        $coupon = null;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
        }

        $finalTotal = $total - $discount;
        $shippingFee = \App\Models\Setting::getShippingFee($finalTotal);
        $finalTotal += $shippingFee;

        $provinces = config('vietnam_provinces');

        return view('frontend.checkout.index', compact('cart', 'total', 'coupon', 'discount', 'shippingFee', 'finalTotal', 'provinces'));
    }

    /**
     * AJAX: Kiểm tra giỏ hàng trước khi chuyển sang checkout
     */
    public function validateCart()
    {
        $cart = $this->cartService->getCart();

        if (empty($cart)) {
            return response()->json(['valid' => false, 'message' => 'Giỏ hàng trống!']);
        }

        $errors = [];
        foreach ($cart as $variantId => $item) {
            $variant = ProductVariant::with('product')->find($variantId);

            if (!$variant || !$variant->product) {
                $errors[] = [
                    'name' => $item['name'] ?? 'Sản phẩm',
                    'issue' => 'không còn tồn tại trong hệ thống',
                    'type' => 'not_found',
                ];
                continue;
            }

            if (!$variant->product->is_active) {
                $errors[] = [
                    'name' => $item['name'],
                    'issue' => 'đã ngưng kinh doanh',
                    'type' => 'inactive',
                ];
                continue;
            }

            if ($variant->stock_quantity <= 0) {
                $errors[] = [
                    'name' => $item['name'],
                    'issue' => 'đã hết hàng',
                    'type' => 'out_of_stock',
                ];
                continue;
            }

            if ($item['quantity'] > $variant->stock_quantity) {
                $errors[] = [
                    'name' => $item['name'],
                    'issue' => 'chỉ còn ' . $variant->stock_quantity . ' sản phẩm (bạn chọn ' . $item['quantity'] . ')',
                    'type' => 'insufficient_stock',
                    'available' => $variant->stock_quantity,
                ];
            }
        }

        if (!empty($errors)) {
            return response()->json(['valid' => false, 'errors' => $errors]);
        }

        return response()->json(['valid' => true]);
    }

    public function store(Request $request)
    {
        $provinces = config('vietnam_provinces');
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'regex:/^(03|05|07|08|09)\d{8}$/'],
            'email' => 'required|email:rfc,dns|max:255',
            'province' => 'required|string|in:'.implode(',', $provinces),
            'address' => 'required|string|max:500',
            'note' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:COD,BANK_TRANSFER,VNPAY',
            'shipping_provider' => 'nullable|string',
            'shipping_service_name' => 'nullable|string',
            'shipping_fee' => 'nullable|numeric',
        ], [
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại phải bắt đầu bằng 03, 05, 07, 08 hoặc 09 và có đúng 10 chữ số.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'province.required' => 'Vui lòng chọn tỉnh thành.',
            'province.in' => 'Tỉnh thành không hợp lệ.',
        ]);

        $cart = $this->cartService->getCart();
        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        try {
            DB::beginTransaction();

            // Get coupon and shipping data
            $couponCode = session()->get('coupon_code');
            $discount = session()->get('discount_amount', 0);

            // Calculate shipping fee (Get from request or default to old way)
            $shippingFee = $request->input('shipping_fee');
            if ($shippingFee === null) {
                // Dự phòng nếu không có phí ship gửi lên
                $shippingFee = \App\Models\Setting::getShippingFee($total - $discount);
            }
            $shippingProvider = $request->input('shipping_provider');
            $shippingServiceName = $request->input('shipping_service_name');

            $finalTotal = $total - $discount + $shippingFee;

            $order = Order::create([
                'user_id' => Auth::id(), // Nullable if guest
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'province' => $request->province,
                'address' => $request->address,
                'status' => 'pending',
                'total_price' => $total,
                'coupon_code' => $couponCode,
                'discount_amount' => $discount,
                'shipping_fee' => $shippingFee,
                'shipping_provider' => $shippingProvider,
                'shipping_service_name' => $shippingServiceName,
                'final_total' => $finalTotal,
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->address.', '.$request->province.' - '.$request->phone.' - '.$request->name,
                'note' => $request->note,
            ]);

            // Increment coupon used_count if coupon was applied
            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                if ($coupon) {
                    $coupon->increment('used_count');
                }
            }

            foreach ($cart as $id => $details) {
                // Trừ kho với lockForUpdate để tránh race condition
                $variant = ProductVariant::where('id', $details['variant_id'])->lockForUpdate()->first();
                if (!$variant || $variant->stock_quantity < $details['quantity']) {
                    throw new \Exception('Sản phẩm "' . $details['name'] . '" không đủ số lượng tồn kho.');
                }
                $variant->decrement('stock_quantity', $details['quantity']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $details['product_id'],
                    'variant_id' => $details['variant_id'],
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'cost_price' => $details['price'],
                ]);
            }

            DB::commit();

            // Notify Admins
            $admins = User::getAdmins();
            Notification::send($admins, new NewOrderNotification($order));

            // Clear cart and coupon session
            $this->cartService->clearCart();
            Session::forget(['coupon_code', 'discount_amount']);

            // Nếu chọn VNPAY -> redirect sang trang thanh toán VNPAY
            if ($request->payment_method === 'VNPAY') {
                return redirect()->route('vnpay.payment', $order->id);
            }

            // Mark any abandoned carts as recovered for this user/session
            try {
                app(\App\Services\ConversionTrackingService::class)->markRecovered(
                    Auth::id(),
                    session()->getId()
                );
            } catch (\Exception $e) {
                \Log::warning('Cart abandonment recovery tracking failed: ' . $e->getMessage());
            }

            // COD & BANK_TRANSFER: gửi email xác nhận và chuyển đến trang thành công
            try {
                \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\OrderConfirmationMail($order));
            } catch (\Exception $e) {
                \Log::error('Có lỗi xảy ra khi gửi email xác nhận đặt hàng: '.$e->getMessage());
            }

            // Set session for guest verification if not logged in
            if (!Auth::check()) {
                session(['verified_order_id' => $order->id]);
            }

            return redirect()->route('checkout.success', $order->id)->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Order error: '.$e->getMessage())->withInput();
        }
    }

    public function success($id)
    {
        $order = Order::with(['items.product', 'items.variant'])->findOrFail($id);

        // Lấy thông tin tài khoản ngân hàng mặc định
        $bank = \App\Models\BankSetting::where('is_active', true)->where('is_default', true)->first();
        
        // Nếu không có mặc định, lấy cái đầu tiên đang hoạt động
        if (!$bank) {
            $bank = \App\Models\BankSetting::where('is_active', true)->first();
        }

        $bankName = $bank->bank_name ?? 'Vietcombank';
        $bankAccount = $bank->account_number ?? '0071001234567';
        $bankOwner = $bank->account_name ?? 'CÔNG TY TNHH SIÊU NHÂN GAO';
        $bankId = $bank->bank_id ?? 'vcb';

        return view('frontend.checkout.success', compact('order', 'bankName', 'bankAccount', 'bankOwner', 'bankId'));
    }

    /**
     * Xác nhận đã chuyển khoản
     */
    public function confirmTransfer($id)
    {
        $order = Order::findOrFail($id);

        if ($order->payment_method !== 'BANK_TRANSFER') {
            return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ.');
        }

        if ($order->payment_status !== 'pending') {
            return redirect()->back()->with('error', 'Trạng thái thanh toán không hợp lệ.');
        }

        $order->update([
            'payment_status' => 'waiting_confirmation'
        ]);

        // Ghi lại lịch sử (nếu có hệ thống lịch sử đơn hàng)
        if (class_exists(\App\Models\OrderHistory::class)) {
            \App\Models\OrderHistory::create([
                'order_id' => $order->id,
                'status' => $order->status,
                'note' => 'Khách hàng xác nhận đã chuyển khoản. Chờ Admin kiểm tra.',
                'user_id' => Auth::id()
            ]);
        }

        return redirect()->back()->with('success', 'Thông báo đã được gửi. Vui lòng chờ chúng tôi xác nhận giao dịch.');
    }

    /**
     * Hủy đơn hàng khi đang chờ thanh toán
     */
    public function cancelOrder($id, \App\Services\OrderService $orderService)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng ở trạng thái hiện tại.');
        }

        try {
            $orderService->updateOrderStatus($order, Order::STATUS_CANCELLED, Auth::user(), 'Khách hàng tự hủy đơn hàng từ trang thanh toán.');

            return redirect()->route('shop')->with('success', 'Đơn hàng đã được hủy thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi hủy đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Apply coupon code
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        $cart = $this->cartService->getCart();
        if (count($cart) == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ], 400);
        }

        // Calculate cart total
        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        // Find coupon
        $couponCode = strtoupper(trim($request->coupon_code));
        $coupon = Coupon::where('code', $couponCode)->first();

        if (! $coupon) {
            return response()->json([
                'success' => false,
                'message' => __('Mã giảm giá không tồn tại.'),
            ], 404);
        }

        // Validate coupon
        if (! $coupon->is_active) {
            return response()->json([
                'success' => false,
                'message' => __('Mã giảm giá này hiện không còn hoạt động.'),
            ], 400);
        }

        if ($coupon->isNotYetStarted()) {
            return response()->json([
                'success' => false,
                'message' => __('Mã giảm giá này chưa đến thời gian sử dụng.'),
            ], 400);
        }

        if ($coupon->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => __('Mã giảm giá này đã hết hạn.'),
            ], 400);
        }

        if ($coupon->hasReachedUsageLimit()) {
            return response()->json([
                'success' => false,
                'message' => __('Mã giảm giá này đã hết lượt sử dụng.'),
            ], 400);
        }

        // Check if coupon belongs to this user
        if ($coupon->user_id && $coupon->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('Mã giảm giá này không dành cho tài khoản của bạn.'),
            ], 400);
        }

        if ($coupon->min_order_amount && $total < $coupon->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => __('Đơn hàng tối thiểu :amount để sử dụng mã này.', ['amount' => number_format($coupon->min_order_amount).' đ']),
            ], 400);
        }

        // Calculate discount
        $discount = $coupon->calculateDiscount($total);
        $finalTotal = $total - $discount;

        // Save to session
        session()->put('coupon_code', $coupon->code);
        session()->put('discount_amount', $discount);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'data' => [
                'coupon_code' => $coupon->code,
                'discount' => $discount,
                'discount_formatted' => number_format($discount).' đ',
                'final_total' => $finalTotal,
                'final_total_formatted' => number_format($finalTotal).' đ',
            ],
        ]);
    }

    /**
     * Remove applied coupon
     */
    public function removeCoupon()
    {
        session()->forget(['coupon_code', 'discount_amount']);

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed.',
        ]);
    }
}
