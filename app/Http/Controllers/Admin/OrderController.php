<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.variant.sizeRelationship', 'items.variant.colorRelationship']);
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
    public function update(Request $request, Order $order)
    {
        // Standardize to UPPERCASE to match Frontend View logic
        $request->validate([
            'status' => 'required|in:PENDING,CONFIRMED,SHIPPED,COMPLETED,CANCELLED,pending,confirmed,shipped,completed,cancelled',
        ]);

        $oldStatus = $order->status; // Model accessor might be used, but raw DB value is safest if enum is consistent. 
        // Actually $order->status returns string from DB. 
        // Let's ensure we use defined constants or lowercase/uppercase consistently.
        // The DB enum is lowercase: 'pending', 'confirmed'...
        // The Controller seemed to force UPPERCASE in previous code, but DB is lowercase.
        // Let's fix the controller to use lowercase to match DB and Model constants.
        
        $newStatus = strtolower($request->input('status'));

        if (!$order->canTransitionTo($newStatus)) {
            return back()->with('error', 'Không thể chuyển trạng thái từ ' . $order->status_text . ' sang ' . $this->getStatusText($newStatus));
        }

        // Nếu đơn hàng bị hủy và trước đó chưa hủy -> Hoàn lại kho
        if ($newStatus == Order::STATUS_CANCELLED && $oldStatus != Order::STATUS_CANCELLED) {
            foreach ($order->items as $item) {
                if ($item->variant) {
                    $item->variant->increment('stock_quantity', $item->quantity);
                }
            }
        }

        $order->update([
            'status' => $newStatus,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Trạng thái đơn hàng đã được cập nhật thành công.' . ($newStatus == 'CANCELLED' ? ' (Đã hoàn kho)' : ''));
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
}
