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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'province' => 'required|string',
            'address' => 'required|string',
            'payment_method' => 'required|string',
            'status' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

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
                'user_id' => $request->user_id ?? null,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'province' => $request->province,
                'address' => $request->address,
                'status' => $request->status,
                'total_price' => $totalPrice,
                'final_total' => $totalPrice, // No discount/shipping for manual admin order in this basic impl
                'payment_method' => $request->payment_method,
                'payment_status' => $request->status === Order::STATUS_COMPLETED ? 'paid' : 'pending',
                'shipping_address' => $request->address . ', ' . $request->province,
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
    public function update(Request $request, Order $order, \App\Services\OrderService $orderService)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $newStatus = $request->input('status');

        try {
            $orderService->updateOrderStatus($order, $newStatus, Auth::user());
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
}
