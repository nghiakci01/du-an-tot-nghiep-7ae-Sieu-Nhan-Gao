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
        $order->load(['user', 'items.product', 'items.variant']);
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

        $oldStatus = strtoupper($order->status); // Ensure we compare normalized upper
        $newStatus = strtoupper($request->input('status')); // Force Save as Upper

        // Nếu đơn hàng bị hủy và trước đó chưa hủy -> Hoàn lại kho
        if ($newStatus == 'CANCELLED' && $oldStatus != 'CANCELLED') {
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
}
