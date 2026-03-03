<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestOrderController extends Controller
{
    public function show($id)
    {
        // Must be a guest OR explicitly verified
        if (Auth::check()) {
            return redirect()->route('account.orders.show', $id);
        }

        // Guest access verification
        if (session('verified_order_id') != $id) {
            return redirect()->route('order-tracking.index')
                ->with('error', 'Vui lòng xác thực thông tin đơn hàng để xem chi tiết.');
        }

        $order = Order::with(['items.product', 'histories'])->findOrFail($id);
        $user = null;
        $userReviews = collect();

        return view('frontend.account.orders.show', compact('user', 'order', 'userReviews'));
    }
}
