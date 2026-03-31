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

        // Filter by Delivery Status
        if (request()->has('delivery_status') && request()->delivery_status != '') {
            match (request()->delivery_status) {
                'unassigned' => $query->where('status', Order::STATUS_CONFIRMED)->whereNull('shipper_id'),
                'delivering' => $query->whereIn('status', [Order::STATUS_SHIPPED]),
                'completed'  => $query->where('status', Order::STATUS_COMPLETED),
                default      => null
            };
        }

        $orders = $query->paginate(10)->appends(request()->all());

        $shippers = User::where('role', User::ROLE_STAFF)->get();

        return view('admin.orders.index', compact('orders', 'shippers'));
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
            'payment_method' => 'required|in:COD,BANK_TRANSFER,CASH,VNPAY',
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
        $shippers = User::where('role', User::ROLE_STAFF)->get();

        return view('admin.orders.show', compact('order', 'shippers'));
    }

    /**
     * Assign a shipper to the order
     */
    public function assignShipper(Request $request, Order $order)
    {
        $request->validate([
            'shipper_id' => 'required|exists:users,id',
        ]);

        $shipper = User::findOrFail($request->shipper_id);

        if (!$shipper->isStaff()) {
            return back()->with('error', 'Người dùng được chọn không phải là nhân viên giao hàng.');
        }

        try {
            DB::beginTransaction();

            $order->update(['shipper_id' => $shipper->id]);

            // Log history
            \App\Models\OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'new_status' => $order->status,
                'note' => 'Admin đã gán nhân viên giao hàng: ' . $shipper->name,
            ]);

            // Notify Shipper
            $shipper->notify(new \App\Notifications\ShipperAssignedNotification($order));

            DB::commit();

            return back()->with('success', 'Đã gán nhân viên ' . $shipper->name . ' cho đơn hàng thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
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

    public function triggerAutoCancel(Request $request)
    {
        $minutes = $request->input('auto_cancel_unpaid_order_minutes');
        
        if ($minutes && is_numeric($minutes) && $minutes >= 5) {
            \App\Models\Setting::updateOrCreate(
                ['key' => 'auto_cancel_unpaid_order_minutes'],
                ['value' => $minutes]
            );
        }

        try {
            \Illuminate\Support\Facades\Artisan::call('app:check-payment-reminders');
            $output = \Illuminate\Support\Facades\Artisan::output();
            
            return redirect()->back()->with('success', 'Đã lưu cấu hình (' . $minutes . ' phút) và chạy lệnh rà soát đơn hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi khi chạy rà soát đơn hàng: ' . $e->getMessage());
        }
    }
}
