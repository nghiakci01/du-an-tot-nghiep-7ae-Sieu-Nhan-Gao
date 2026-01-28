<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return view('frontend.checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'note' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:COD,BANK_TRANSFER',
        ]);

        $cart = session()->get('cart', []);
        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(), // Nullable if guest
                'status' => 'PENDING',
                'total_price' => $total,
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->address . ' - ' . $request->phone . ' - ' . $request->name, // Simple concatenation or store separately if schema supported
                'note' => $request->note,
            ]);

            foreach ($cart as $id => $details) {
                // Verify stock again
                $variant = ProductVariant::find($details['variant_id']);
                if (!$variant || $variant->stock_quantity < $details['quantity']) {
                    throw new \Exception('Sản phẩm ' . $details['name'] . ' (' . $details['size'] . '/' . $details['color'] . ') không đủ hàng.');
                }

                // Deduct stock
                $variant->decrement('stock_quantity', $details['quantity']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $details['product_id'],
                    'variant_id' => $details['variant_id'],
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);
            }

            DB::commit();
            Session::forget('cart');

            return redirect()->route('checkout.success', $order->id)->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi đặt hàng: ' . $e->getMessage())->withInput();
        }
    }

    public function success($id)
    {
        $order = Order::findOrFail($id);
        
        // Security check: Only allow viewing if Auth user matches Or if just created (session check could be added here for strictness)
        if (Auth::check() && $order->user_id !== Auth::id()) {
             return redirect()->route('welcome');
        }

        return view('frontend.checkout.success', compact('order'));
    }
}
