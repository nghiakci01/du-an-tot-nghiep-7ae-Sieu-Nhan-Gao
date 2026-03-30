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

    public function search(Request $request)
    {
        // Normalize inputs
        $orderId = trim(str_replace('#', '', $request->input('order_id')));
        $contact = trim($request->input('contact'));

        // Merge back to request for validation if needed, though we can just validate the variables
        $request->merge([
            'order_id' => $orderId,
            'contact' => $contact,
        ]);

        $request->validate([
            'order_id' => 'required|string',
            'contact' => 'required|string', // Can be Email or Phone
        ], [
            'order_id.required' => 'Vui lòng nhập mã đơn hàng.',
            'contact.required' => 'Vui lòng nhập Email hoặc Số điện thoại.',
        ]);

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
