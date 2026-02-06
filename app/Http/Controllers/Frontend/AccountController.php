<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->latest()->get();
        return view('frontend.account.index', compact('user', 'orders'));
    }

    public function updateDetails(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            // Store new avatar in 'avatars' folder within public disk
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function viewOrder($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->with('items.product', 'items.variant')->firstOrFail();
        return view('frontend.account.order_detail', compact('order'));
    }

    public function cancelOrder($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Check for UPPERCASE status 'PENDING' to match DB convention
        if ($order->status == 'PENDING') {
            $order->status = 'CANCELLED'; // Save as UPPERCASE
            $order->save();

            // Replicate stock restore logic here for User-initiated cancellation
            foreach ($order->items as $item) {
                if ($item->variant) {
                    $item->variant->increment('stock_quantity', $item->quantity);
                }
            }

            return back()->with('success', 'Đã hủy đơn hàng thành công.');
        }

        return back()->with('error', 'Không thể hủy đơn hàng này.');
    }
}
