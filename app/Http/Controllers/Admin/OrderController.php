<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Order::with('user')->latest();

        if (request()->has('status') && request()->status != '') {
            $query->where('status', request('status'));
        }

        $orders = $query->paginate(10)->appends(request()->all());

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = config('vietnam_provinces', []);
        return view('admin.orders.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_type' => 'required|in:EXISTING,NEW',
            'user_id' => 'required_if:customer_type,EXISTING|nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'province' => 'required|string',
            'address' => 'required|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:COD,BANK_TRANSFER,CASH',
            'status' => 'required|string',
            'manual_discount' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $totalPrice = 0;
            foreach ($request->items as $item) {
                $variant = ProductVariant::where('id', $item['variant_id'])->lockForUpdate()->first();
                
                if (!$variant) {
                    throw new \Exception('Sản phẩm không tồn tại.');
                }

                if ($variant->stock_quantity < $item['quantity']) {
                    $productName = $variant->product->name;
                    $variantInfo = ($variant->sizeRelationship ? $variant->sizeRelationship->name : ( $variant->size ?: '' )) . 
                                   ' - ' . 
                                   ($variant->colorRelationship ? $variant->colorRelationship->name : ( $variant->color ?: '' ));
                    throw new \Exception("Sản phẩm '{$productName}' ({$variantInfo}) chỉ còn {$variant->stock_quantity} trong kho.");
                }

                $totalPrice += ($variant->price ?? $variant->product->price) * $item['quantity'];
            }

            $discount = $request->input('manual_discount', 0);
            $finalTotal = $totalPrice - $discount;
            $shippingFee = \App\Models\Setting::getShippingFee($finalTotal);
            $finalTotal += $shippingFee;

            $order = Order::create([
                'user_id' => $request->customer_type === 'EXISTING' ? $request->user_id : null,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'province' => $request->province,
                'address' => $request->address,
                'status' => $request->status,
                'total_price' => $totalPrice,
                'discount_amount' => $discount,
                'shipping_fee' => $shippingFee,
                'final_total' => $finalTotal,
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->address . ', ' . $request->province . ' - ' . $request->phone . ' - ' . $request->name,
                'note' => $request->note,
            ]);

            foreach ($request->items as $item) {
                $variant = ProductVariant::find($item['variant_id']);
                
                // Deduct stock
                $variant->decrement('stock_quantity', $item['quantity']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->id,
                    'quantity' => $item['quantity'],
                    'price' => $variant->price ?? $variant->product->price,
                ]);
            }

            // Log history
            \App\Models\OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'new_status' => $order->status,
                'note' => 'Đơn hàng được tạo thủ công bởi ' . auth()->user()->name,
            ]);

            DB::commit();

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Đơn hàng đã được tạo thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function customersSearch(Request $request)
    {
        $q = $request->q;
        $users = User::where('name', 'like', "%$q%")
            ->orWhere('email', 'like', "%$q%")
            ->orWhere('phone', 'like', "%$q%")
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.variant', 'histories.user']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order, \App\Services\OrderService $orderService)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $newStatus = $request->input('status');

        try {
            $orderService->updateOrderStatus($order, $newStatus, auth()->user());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Trạng thái đơn hàng đã được cập nhật thành công.');
    }

    /**
     * Print the specified resource.
     */
    public function print(Order $order)
    {
        $order->load(['user', 'items.product', 'items.variant']);

        return view('admin.orders.print', compact('order'));
    }

    public function confirmPayment(Order $order)
    {
        if ($order->payment_method !== 'BANK_TRANSFER' || $order->payment_status !== 'waiting_confirmation') {
            return back()->with('error', 'Đơn hàng không ở trạng thái chờ xác nhận thanh toán.');
        }

        try {
            DB::beginTransaction();

            $order->update([
                'payment_status' => 'paid',
                'status' => Order::STATUS_CONFIRMED
            ]);

            // Log history
            \App\Models\OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'previous_status' => 'waiting_confirmation',
                'new_status' => Order::STATUS_CONFIRMED,
                'note' => 'Admin xác nhận đã nhận tiền chuyển khoản. Đơn hàng chuyển sang trạng thái Đã xác nhận.',
            ]);

            DB::commit();

            return back()->with('success', 'Đã xác nhận thanh toán cho đơn hàng #' . $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if ($order->status !== Order::STATUS_CANCELLED && $order->status !== Order::STATUS_COMPLETED) {
            return back()->with('error', 'Chỉ có thể xóa đơn hàng đã hoàn thành hoặc đã hủy.');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Đơn hàng đã được xóa thành công.');
    }

    private function getStatusText($status)
    {
        return match ($status) {
            Order::STATUS_PENDING => 'Chờ xác nhận',
            Order::STATUS_CONFIRMED => 'Đã xác nhận',
            Order::STATUS_SHIPPED => 'Đang giao hàng',
            Order::STATUS_COMPLETED => 'Hoàn thành',
            Order::STATUS_CANCELLED => 'Đã hủy',
            default => 'Không xác định',
        };
    }

    /**
     * Kích hoạt ép buộc lệnh tự động hủy đơn ngay lập tức từ Admin Panel.
     */
    public function triggerAutoCancel(Request $request)
    {
        try {
            // Lấy thời gian do admin vừa điền trên Form (nếu có, mặc định là 24)
            $hours = $request->input('auto_cancel_unpaid_order_hours', 24);

            // Lưu lại mức này vào CSDL để áp dụng cho cả hệ thống chạy ngầm
            \App\Models\Setting::updateOrCreate(
                ['key' => 'auto_cancel_unpaid_order_hours'],
                ['value' => $hours]
            );
            
            \Illuminate\Support\Facades\Cache::forget('global_settings');

            // Gọi chạy ngay lệnh Artisan trong nền
            \Illuminate\Support\Facades\Artisan::call('orders:cancel-unpaid', [
                '--hours' => $hours
            ]);

            $output = \Illuminate\Support\Facades\Artisan::output();

            return back()->with('success', 'Đã lưu cấu hình và đối chiếu xử lý: ' . $output);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi thực thi lệnh tự động hủy: ' . $e->getMessage());
        }
    }

    /**
     * Query VNPAY payment status for an order (QueryDR).
     */
    public function queryPayment(Order $order, \App\Services\VnpayService $vnpayService)
    {
        if ($order->payment_method !== 'VNPAY') {
            return response()->json(['success' => false, 'message' => 'Đơn hàng không thanh toán qua VNPAY.']);
        }

        $result = $vnpayService->queryTransaction($order);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => \App\Services\VnpayService::getResponseMessage($result['vnp_ResponseCode'] ?? '99'),
        ]);
    }

    /**
     * Request refund via VNPAY for an order.
     */
    public function refundPayment(Request $request, Order $order, \App\Services\VnpayService $vnpayService)
    {
        if ($order->payment_method !== 'VNPAY' || $order->payment_status !== 'paid') {
            return back()->with('error', 'Chỉ có thể hoàn tiền cho đơn VNPAY đã thanh toán.');
        }

        $amount = $request->input('refund_amount', $order->final_total);
        $transactionType = $amount >= $order->final_total ? '02' : '03'; // 02 = full, 03 = partial

        $result = $vnpayService->refundTransaction($order, (int) $amount, auth()->user()->email, $transactionType);

        $responseCode = $result['vnp_ResponseCode'] ?? '99';
        $message = \App\Services\VnpayService::getResponseMessage($responseCode);

        if ($responseCode === '00') {
            $order->update(['payment_status' => 'refunded']);

            \App\Models\OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'previous_status' => 'paid',
                'new_status' => $order->status,
                'note' => 'Hoàn tiền VNPAY thành công: ' . number_format($amount) . 'đ',
            ]);

            return back()->with('success', 'Hoàn tiền VNPAY thành công: ' . number_format($amount) . 'đ');
        }

        return back()->with('error', 'Hoàn tiền VNPAY thất bại: ' . $message);
    }
}
