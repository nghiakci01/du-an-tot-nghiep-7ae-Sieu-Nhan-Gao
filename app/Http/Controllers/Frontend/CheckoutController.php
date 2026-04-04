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
use App\Notifications\OrderPlacedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;

use App\Services\CartService;
use App\Services\Shipping\ShippingService;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $shippingService;

    public function __construct(CartService $cartService, ShippingService $shippingService)
    {
        $this->cartService = $cartService;
        $this->shippingService = $shippingService;
    }

    public function index(Request $request)
    {
        $cart = $this->cartService->getCart();

        // Lọc giỏ hàng theo các item đã chọn (nêu có trong session)
        $selectedIds = session('selected_checkout_ids');
        if ($selectedIds && is_array($selectedIds)) {
            $selectedIds = array_map('strval', $selectedIds);
            $cart = array_filter($cart, function ($key) use ($selectedIds) {
                return in_array(strval($key), $selectedIds);
            }, ARRAY_FILTER_USE_KEY);
        } else {
            // Nếu không có selection trong session, redirect về giỏ hàng
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn sản phẩm trong giỏ hàng trước khi thanh toán.');
        }

        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Validate tồn kho trước khi vào trang checkout
        $invalidItems = [];
        $productQtyTracker = [];

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

            // Track per-product totals to enforce 10-limit
            $pid = $item['product_id'];
            $productQtyTracker[$pid] = ($productQtyTracker[$pid] ?? 0) + $item['quantity'];
            if ($productQtyTracker[$pid] > 10) {
                $invalidItems[] = '“' . $item['name'] . '” đã vượt quá giới hạn mua tối đa 10 cái/khách hàng.';
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

        $userAddresses = collect();
        if (auth()->check()) {
            $userAddresses = auth()->user()->addresses()->orderByDesc('is_default')->get();
        }

        $defaultBank = \App\Models\BankSetting::where('is_default', true)->first()
            ?? \App\Models\BankSetting::first();

        return view('frontend.checkout.index', compact(
            'cart', 'total', 'coupon', 'discount', 'shippingFee', 'finalTotal',
            'provinces', 'userAddresses', 'defaultBank'
        ));
    }

    /**
     * AJAX: Kiểm tra giỏ hàng trước khi chuyển sang checkout
     */
    public function validateCart(Request $request)
    {
        $cart = $this->cartService->getCart();

        // Lọc các item được chọn
        $selectedIds = $request->input('ids');
        if ($selectedIds) {
            if (is_string($selectedIds)) {
                $selectedIds = array_filter(explode(',', $selectedIds));
            }
            // Chuyển tất cả về string để so khớp chính xác
            $selectedIds = array_values(array_map('strval', (array) $selectedIds));

            $cart = array_filter($cart, function ($key) use ($selectedIds) {
                return in_array(strval($key), $selectedIds);
            }, ARRAY_FILTER_USE_KEY);

            // Lưu vào session để trang checkout sử dụng
            session(['selected_checkout_ids' => $selectedIds]);
        } else {
            // Nếu không gửi ids lên, ta không cho phép checkout (hoặc clear session cũ)
            session()->forget('selected_checkout_ids');
            return response()->json(['valid' => false, 'message' => 'Vui lòng chọn ít nhất một sản phẩm để thanh toán!']);
        }

        if (empty($cart)) {
            return response()->json(['valid' => false, 'message' => 'Vui lòng chọn ít nhất một sản phẩm để thanh toán!']);
        }

        $errors = [];
        $productQtyTracker = [];

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
                continue;
            }

            // Track per-product limit
            $pid = $item['product_id'];
            $productQtyTracker[$pid] = ($productQtyTracker[$pid] ?? 0) + $item['quantity'];
            if ($productQtyTracker[$pid] > 10) {
                $errors[] = [
                    'name' => $item['name'],
                    'issue' => 'vượt khóa giới hạn mua 10 cái/sản phẩm.',
                    'type' => 'exceeded_limit',
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
        Log::info('Checkout process started', [
            'user_id' => Auth::id(),
            'ip' => $request->ip(),
            'payment_method' => $request->input('payment_method'),
            'cart_count' => count($this->cartService->getCart())
        ]);

        $provinces = config('vietnam_provinces');

        // Normalize province name (remove prefixes like "Tỉnh " or "Thành phố ")
        if ($request->has('province')) {
            $normalizedProv = preg_replace('/^(Tỉnh|Thành phố)\s+/u', '', $request->province);
            $request->merge(['province' => $normalizedProv]);
        }

        if ($request->filled('user_address_id')) {
            $userAddr = \App\Models\UserAddress::where('user_id', Auth::id())->find($request->user_address_id);
            if ($userAddr) {
                $request->merge([
                    'name'     => $userAddr->receiver_name,
                    'phone'    => $userAddr->phone,
                    'province' => preg_replace('/^(Tỉnh|Thành phố)\s+/u', '', $userAddr->province),
                    'address'  => $userAddr->address . ($userAddr->commune ? ', ' . $userAddr->commune : ''),
                ]);
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'regex:/^(03|05|07|08|09)\d{8}$/'],
            'email' => 'required|email:rfc,dns|max:255',
            'province' => $request->filled('user_address_id') ? 'required|string' : 'required|string|in:' . implode(',', $provinces),
            'district' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:COD,VNPAY',
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

        // Lọc giỏ hàng theo các item đã chọn
        $selectedIds = session('selected_checkout_ids');
        if ($selectedIds && is_array($selectedIds)) {
            $selectedIds = array_map('strval', $selectedIds);
            $cart = array_filter($cart, function ($key) use ($selectedIds) {
                return in_array(strval($key), $selectedIds);
            }, ARRAY_FILTER_USE_KEY);
        } else {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn sản phẩm trong giỏ hàng trước khi thanh toán.');
        }

        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = 0;
        $totalQuantity = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
            $totalQuantity += $details['quantity'];
        }

        // Giới hạn số lượng sản phẩm cho đơn COD
        if ($request->payment_method === 'COD' && $totalQuantity > 10) {
            return redirect()->back()
                ->with('error', 'Đơn hàng COD chỉ được tối đa 10 sản phẩm (hiện có ' . $totalQuantity . '). Vui lòng giảm số lượng hoặc chọn Chuyển khoản/VNPAY.')
                ->withInput();
        }

        // Kiểm tra chặn giới hạn 10 sản phẩm TẠI MỤC THANH TOÁN (Tránh kẽ hở đã cho vào giỏ từ trước)
        $productQtyTracker = [];
        foreach ($cart as $details) {
            $pid = $details['product_id'];
            $productQtyTracker[$pid] = ($productQtyTracker[$pid] ?? 0) + $details['quantity'];
            if ($productQtyTracker[$pid] > 10) {
                return redirect()->route('cart.index')->with('error', 'Sản phẩm "' . $details['name'] . '" đã vượt qua giới hạn mua cho phép là 10. Vui lòng giảm số lượng trong giỏ hàng.');
            }
        }

        try {
            DB::beginTransaction();

            // Get coupon and shipping data
            $couponCode = session()->get('coupon_code');
            $discount = session()->get('discount_amount', 0);

            // [SECURITY CHECK] Final verification before order creation
            if ($couponCode && Auth::check()) {
                $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
                if ($coupon) {
                    $alreadyUsed = \App\Models\Order::where('user_id', Auth::id())
                        ->where('coupon_code', $couponCode)
                        ->whereNotIn('status', ['cancelled', 'failed'])
                        ->exists();

                    $usedInPivot = \Illuminate\Support\Facades\DB::table('coupon_user')
                        ->where('user_id', Auth::id())
                        ->where('coupon_id', $coupon->id)
                        ->whereNotNull('used_at')
                        ->exists();

                    if ($alreadyUsed || $usedInPivot) {
                        // Clear session coupon data as it's invalid now
                        session()->forget(['coupon_code', 'discount_amount']);
                        throw new \Exception('Bạn đã sử dụng mã giảm giá này cho một đơn hàng khác. Vui lòng kiểm tra lại.');
                    }
                }
            }

            $shippingSubtotal = max(0, $total - $discount);
            $shippingOption = $this->shippingService->resolveSelectedOption(
                'home',
                $request->input('province'),
                $request->input('district'),
                $request->input('ward'),
                $this->shippingService->estimateWeightFromCart($cart),
                $shippingSubtotal,
                $request->input('shipping_provider')
            );
            if ($shippingOption === null) {
                return redirect()->back()
                    ->with('error', 'Vui long chon phuong thuc van chuyen hop le.')
                    ->withInput();
            }
            $shippingFee = (float) ($shippingOption['fee'] ?? 0);
            $shippingProvider = $shippingOption['provider'] ?? null;
            $shippingServiceName = $shippingOption['service_name'] ?? null;
            $finalTotal = $total - $discount + $shippingFee;
            $shippingAddress = trim(implode(', ', array_filter([
                $request->address,
                $request->input('ward'),
                $request->input('district'),
                $request->province,
            ]))) . ' - ' . $request->phone . ' - ' . $request->name;
            $order = Order::create([
                'user_id' => Auth::id(),
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
                'payment_status' => 'pending',
                'shipping_address' => $shippingAddress,
                'note' => $request->note,
            ]);

            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'total' => $order->final_total,
                'status' => $order->status
            ]);

            // Increment coupon used_count and mark as used for user
            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                if ($coupon) {
                    $coupon->increment('used_count');
                    
                    // Mark as used in coupon_user pivot
                    if (Auth::check()) {
                        DB::table('coupon_user')->updateOrInsert(
                            ['user_id' => Auth::id(), 'coupon_id' => $coupon->id],
                            [
                                'used_at' => now(),
                                'order_id' => $order->id,
                                'updated_at' => now()
                            ]
                        );
                    }
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

            // Notify User
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->notify(new OrderPlacedNotification($order));

            // Clear selected items and session
            if ($selectedIds && is_array($selectedIds)) {
                $this->cartService->removeItems($selectedIds);
            } else {
                $this->cartService->clearCart();
            }
            session()->forget(['coupon_code', 'discount_amount', 'selected_checkout_ids']);

            // Mark any abandoned carts as recovered for this user/session
            try {
                app(\App\Services\ConversionTrackingService::class)->markRecovered(
                    Auth::id(),
                    session()->getId()
                );
            } catch (\Exception $e) {
                Log::warning('Cart abandonment recovery tracking failed: ' . $e->getMessage());
            }

            // Nếu là VNPAY: redirect đến cổng thanh toán VNPay
            if ($request->payment_method === 'VNPAY') {
                $vnpayService = app(\App\Services\VnpayService::class);
                $paymentUrl = $vnpayService->getPaymentUrl(
                    $order->id,
                    $finalTotal,
                    $request->input('bank_code')
                );
                return redirect($paymentUrl);
            }

            // COD & BANK_TRANSFER: gửi email xác nhận ngay
            try {
                \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\OrderConfirmationMail($order));
            } catch (\Exception $e) {
                Log::error('Có lỗi xảy ra khi gửi email xác nhận đặt hàng: ' . $e->getMessage());
            }

            return redirect()->route('checkout.success', $order->id)->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Checkout failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Order error: ' . $e->getMessage())->withInput();
        }
    }

    public function success($id)
    {
        $order = Order::with(['items.product', 'items.variant'])->findOrFail($id);

        return view('frontend.checkout.success', compact('order'));
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
                'previous_status' => $order->status,
                'new_status' => 'waiting_confirmation',
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

            // Khôi phục lại giỏ hàng cho khách
            app(CartService::class)->restoreOrderToCart($order);

            return redirect()->route('shop')->with('success', 'Đơn hàng đã được hủy thành công. Các sản phẩm đã được hoàn lại vào giỏ hàng của bạn.');
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

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => __('Mã giảm giá không tồn tại.'),
            ], 404);
        }

        // Validate coupon
        if (!$coupon->is_active) {
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

        // Check if user already used this coupon
        if (Auth::check()) {
            $alreadyUsed = Order::where('user_id', Auth::id())
                ->where('coupon_code', $coupon->code)
                ->whereNotIn('status', ['cancelled', 'failed'])
                ->exists();

            if ($alreadyUsed) {
                return response()->json([
                    'success' => false,
                    'message' => __('Bạn đã sử dụng mã giảm giá này cho một đơn hàng trước đó.'),
                ], 400);
            }

            // Also check pivot table as backup/explicit tracking
            $usedInPivot = DB::table('coupon_user')
                ->where('user_id', Auth::id())
                ->where('coupon_id', $coupon->id)
                ->whereNotNull('claimed_at')
                ->exists();

            if ($usedInPivot) {
                return response()->json([
                    'success' => false,
                    'message' => __('Bạn đã sử dụng mã giảm giá này rồi.'),
                ], 400);
            }
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
                'message' => __('Đơn hàng tối thiểu :amount để sử dụng mã này.', ['amount' => number_format($coupon->min_order_amount) . ' đ']),
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
                'discount_formatted' => number_format($discount) . ' đ',
                'final_total' => $finalTotal,
                'final_total_formatted' => number_format($finalTotal) . ' đ',
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

    /**
     * Xóa sản phẩm hoàn toàn khỏi giỏ hàng từ trang checkout
     */
    public function removeItem($id)
    {
        // 1. Xóa khỏi giỏ hàng chính (DB hoặc Session tùy auth)
        $this->cartService->removeItems([(string)$id]);

        // 2. Xóa khỏi danh sách các item đang được chọn để checkout
        $selectedIds = session('selected_checkout_ids', []);
        if (is_array($selectedIds)) {
            $selectedIds = array_diff(array_map('strval', $selectedIds), [(string)$id]);
            session(['selected_checkout_ids' => array_values($selectedIds)]);
        }

        // 3. Nếu không còn sản phẩm nào để checkout, quay về giỏ hàng
        if (empty(session('selected_checkout_ids'))) {
            return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được xóa. Danh sách thanh toán hiện đang trống.');
        }

        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
}




