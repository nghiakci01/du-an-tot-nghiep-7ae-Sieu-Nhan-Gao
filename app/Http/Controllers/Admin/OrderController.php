<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\Generated\OrderStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $totalPrice = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $variant = ProductVariant::lockForUpdate()->findOrFail($item['variant_id']);

                if ($variant->stock_quantity < $item['quantity']) {
                    throw new \Exception("Sản phẩm {$variant->sku} không đủ tồn kho.");
                }

                $totalPrice += $variant->price * $item['quantity'];
                $itemsData[] = [
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->id,
                    'quantity' => $item['quantity'],
                    'price' => $variant->price,
                    'cost_price' => $variant->price, // Simplified for admin order
                ];

                $variant->decrement('stock_quantity', $item['quantity']);
            }

            $order = Order::create([
                'user_id' => $validated['user_id'] ?? null,
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'],
                'province' => $validated['province'],
                'address' => $validated['address'],
                'status' => $validated['status'],
                'total_price' => $totalPrice,
                'final_total' => $totalPrice, // No discount/shipping for manual admin order in this basic impl
                'payment_method' => $request->payment_method,
                'payment_status' => $request->status === Order::STATUS_COMPLETED ? 'paid' : 'pending',
                'shipping_address' => implode(', ', array_filter([
                    $request->address,
                    $request->input('commune'),
                    $request->province,
                ])),
            ]);

            foreach ($itemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }

            DB::commit();

            return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi tạo đơn hàng: ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
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
    public function update(\App\Http\Requests\Generated\OrderStatusUpdateRequest $request, Order $order, \App\Services\OrderService $orderService)
    {
        $validated = $request->validated();

        $newStatus = $validated['status'];

        try {
            $orderService->updateOrderStatus($order, $newStatus, Auth::user());
            $order->refresh();

            // Tự động tạo mã vận đơn nếu Admin chuyển sang "Đã xác nhận" (Confirmed)
            if ($newStatus === \App\Models\Order::STATUS_CONFIRMED && $this->shouldAutoCreateGhnOrder($order)) {
                try {
                    $ghnProvider = app(\App\Services\Shipping\GhnShippingProvider::class);
                    $ghnProvider->createShippingOrder($order);
                    $order->update([
                        'shipping_provider' => 'ghn',
                        'shipping_service_name' => 'Giao Hàng Nhanh',
                    ]);

                    return redirect()->route('admin.orders.show', $order)
                        ->with('success', 'Trạng thái đơn hàng đã được cập nhật & Đã tự động tạo mã vận đơn GHN thành công!');
                } catch (\Exception $ghnEx) {
                    return redirect()->route('admin.orders.show', $order)
                        ->with('warning', 'Trạng thái đã cập nhật nhưng không thể tạo mã lệnh GHN tự động: ' . $ghnEx->getMessage());
                }
            }

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

    /**
     * Push order to GHN API to get tracking code
     */
    public function pushToGhn(Order $order, \App\Services\Shipping\GhnShippingProvider $ghnProvider)
    {
        if ($order->shipping_provider === 'store_pickup') {
            return back()->with('error', 'Don nhan tai cua hang khong the tao van don GHN.');
        }

        if ($order->tracking_code) {
            return back()->with('error', 'Đơn hàng này đã có mã vận đơn GHN rồi.');
        }

        try {
            $ghnProvider->createShippingOrder($order);
            // Cập nhật nhà cung cấp vận chuyển là GHN
            $order->update([
                'shipping_provider' => 'ghn',
                'shipping_service_name' => 'Giao Hàng Nhanh',
            ]);
            return back()->with('success', 'Đã tạo vận đơn GHN thành công! Mã vận đơn: ' . $order->tracking_code);
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi tạo vận đơn GHN: ' . $e->getMessage());
        }
    }

    protected function shouldAutoCreateGhnOrder(Order $order): bool
    {
        if (filled($order->tracking_code)) {
            return false;
        }

        return $order->shipping_provider === 'ghn' || blank($order->shipping_provider);
    }
}
