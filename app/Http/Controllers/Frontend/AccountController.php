<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->orderBy('created_at', 'desc')->get();
        return view('frontend.account.index', compact('user', 'orders'));
    }

    public function showOrder($id)
    {
        $user = Auth::user();
        $order = $user->orders()->with('items.product')->findOrFail($id);
        return view('frontend.account.orders.show', compact('user', 'order'));
    }
}
