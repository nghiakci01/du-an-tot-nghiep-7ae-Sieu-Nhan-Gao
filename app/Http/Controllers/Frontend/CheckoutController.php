<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'phone' => 'required|string|max:20',
            'province' => 'required|string|in:'.implode(',', $provinces),
            'address' => 'required|string|max:500',
            'note' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:COD,BANK_TRANSFER,VNPAY,ZALOPAY',
        ], [
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

            // Calculate shipping fee
            $shippingFee = \App\Models\Setting::getShippingFee($total - $discount);

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
                // Verify stock again
                $variant = ProductVariant::find($details['variant_id']);
                if (! $variant || $variant->stock_quantity < $details['quantity']) {
                    throw new \Exception('Product '.$details['name'].' ('.$details['size'].'/'.$details['color'].') is out of stock.');
                }

                // Deduct stock
                $variant->decrement('stock_quantity', $details['quantity']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $details['product_id'],
                    'variant_id' => $details['variant_id'],
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);
            }

            DB::commit();

            // Clear cart and coupon session
            Session::forget(['cart', 'coupon_code', 'discount_amount']);

            if ($request->payment_method === 'VNPAY') {
                return app(VnpayController::class)->createPayment($order);
            }

            if ($request->payment_method === 'ZALOPAY') {
                return app(ZaloPayController::class)->pay($order);
            }

            // Send confirmation email for COD and BANK_TRANSFER
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
        $order = Order::findOrFail($id);

        // Security check: Only allow viewing if Auth user matches Or if just created (session check could be added here for strictness)
        $bankName = \App\Models\Setting::get('bank_name', 'Vietcombank');
        $bankAccount = \App\Models\Setting::get('bank_account_number', '0071001234567');
        $bankOwner = \App\Models\Setting::get('bank_account_name', 'CÔNG TY TNHH SIÊU NHÂN GAO');
        $bankId = \App\Models\Setting::get('bank_id', 'vcb');

        return view('frontend.checkout.success', compact('order', 'bankName', 'bankAccount', 'bankOwner', 'bankId'));
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
