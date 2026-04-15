<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index()
    {
        return view('frontend.order-tracking.index');
    }

    public function search(\App\Http\Requests\Generated\OrderTrackingRequest $request)
    {
        // Validation & normalization handled by OrderTrackingRequest

        $order = Order::where('id', $orderId)
            ->where(function ($query) use ($contact) {
                $query->where('email', $contact)
                    ->orWhere('phone', $contact);
            })
            ->with(['items.product', 'items.variant'])
            ->first();

        if (! $order) {
            return redirect()->back()
                ->with('error', 'Không tìm thấy đơn hàng phù hợp với thông tin cung cấp.')
                ->withInput();
        }

        // Set session for order verification regardless of login status
        session(['verified_order_id' => $order->id]);

        return redirect()->route('guest.order.show', $order->id);
    }
}
