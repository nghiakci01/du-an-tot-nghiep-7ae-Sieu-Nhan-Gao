<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                'user_id' => Auth::id(),
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
