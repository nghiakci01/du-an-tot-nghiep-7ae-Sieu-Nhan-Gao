<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
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

        $cart = session()->get('cart', []);
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
                // Removed stock verification and deduction logic as warehouse management is removed

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $details['product_id'],
                    'variant_id' => $details['variant_id'],
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'cost_price' => $details['price'], // We can approximate cost_price or leave it 0 if cost_price logic was in variant
                ]);
            }

            DB::commit();

            // Notify Admins
            $admins = User::getAdmins();
            Notification::send($admins, new NewOrderNotification($order));

            // Clear cart and coupon session
            Session::forget(['cart', 'coupon_code', 'discount_amount']);

            // Nếu chọn VNPAY -> redirect sang trang thanh toán VNPAY
            if ($request->payment_method === 'VNPAY') {
                return redirect()->route('vnpay.payment', $order->id);
            }

            // COD & BANK_TRANSFER: gửi email xác nhận và chuyển đến trang thành công
            try {
                \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\OrderConfirmationMail($order));
            } catch (\Exception $e) {
                \Log::error('Có lỗi xảy ra khi gửi email xác nhận đặt hàng: '.$e->getMessage());
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
    public function cancelOrder($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng ở trạng thái hiện tại.');
        }

        try {
            DB::beginTransaction();

            $order->update(['status' => 'cancelled']);

            // Hoàn lại số lượng tồn kho logic removed

            if (class_exists(\App\Models\OrderHistory::class)) {
                \App\Models\OrderHistory::create([
                    'order_id' => $order->id,
                    'status' => 'cancelled',
                    'note' => 'Khách hàng tự hủy đơn hàng.',
                    'user_id' => Auth::id()
                ]);
            }

            DB::commit();

            return redirect()->route('shop')->with('success', 'Đơn hàng đã được hủy thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
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

        $cart = session()->get('cart', []);
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
