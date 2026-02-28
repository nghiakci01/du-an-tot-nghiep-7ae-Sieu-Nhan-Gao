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
        $request->validate([
            'order_id' => 'required|string',
            'contact' => 'required|string', // Can be Email or Phone
        ], [
            'order_id.required' => 'Vui lòng nhập mã đơn hàng.',
            'contact.required' => 'Vui lòng nhập Email hoặc Số điện thoại.',
        ]);

        $order = Order::where('id', $request->order_id)
            ->where(function ($query) use ($request) {
                $query->where('email', $request->contact)
                      ->orWhere('phone', $request->contact);
            })
            ->with(['items.product', 'items.variant'])
            ->first();

        if (!$order) {
            return redirect()->back()
                ->with('error', 'Không tìm thấy đơn hàng phù hợp với thông tin cung cấp.')
                ->withInput();
        }

        return view('frontend.order-tracking.show', compact('order'));
    }
}
