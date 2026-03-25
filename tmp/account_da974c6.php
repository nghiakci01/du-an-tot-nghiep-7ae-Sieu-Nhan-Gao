<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\UserBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            $orders = $user->orders()->orderBy('created_at', 'desc')->paginate(10);
            $coupons = \App\Models\Coupon::where(function ($q) use ($user) {
                $q->whereNull('user_id')->orWhere('user_id', $user->id);
            })
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->whereRaw('used_count < usage_limit')
                ->get();
            $wishlists            = $user->wishlists()->with('product')->get();
            $userBankAccounts     = $user->bankAccounts()->get();
            $walletTransactions   = $user->walletTransactions()->take(20)->get();
            $walletTopupRequests  = $user->walletTopupRequests()->take(10)->get();
            $walletWithdrawRequests = $user->walletWithdrawRequests()->take(10)->get();
        } else {
            $orders   = collect();
            $coupons  = collect();
            $wishlists = collect();
            $userBankAccounts    = collect();
            $walletTransactions  = collect();
            $walletTopupRequests = collect();
            $walletWithdrawRequests = collect();
        }

        return view('frontend.account.index', compact(
            'user', 'orders', 'coupons', 'wishlists',
            'userBankAccounts', 'walletTransactions', 'walletTopupRequests', 'walletWithdrawRequests'
        ));

    }

    public function showOrder($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user) {
            $order = $user->orders()->with(['items.product', 'histories'])->findOrFail($id);
        } else {
            // Guest access verification
            if (session('verified_order_id') != $id) {
                return redirect()->route('order-tracking.index')
                    ->with('error', 'Vui lòng xác thực thông tin đơn hàng để xem chi tiết.');
            }
            $order = Order::with(['items.product', 'histories'])->findOrFail($id);
        }

        // Lấy danh sách product_id đã được user review trong đơn hàng này
        $productIds = $order->items->pluck('product_id')->filter()->unique();
        $userReviews = collect();
        
        if ($user) {
            $userReviews = Review::where('user_id', $user->id)
                ->whereIn('product_id', $productIds)
                ->get()
                ->keyBy('product_id');
        }

        return view('frontend.account.orders.show', compact('user', 'order', 'userReviews'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['nullable', 'string', 'regex:/^(03|05|07|08|09)\d{8}$/'],
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'required_with:new_password|nullable',
            'new_password' => 'nullable|min:8|confirmed',
        ], [
            'phone.regex' => 'Số điện thoại phải bắt đầu bằng 03, 05, 07, 08 hoặc 09 và có đúng 10 chữ số.',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('new_password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Information updated successfully!');
    }

    public function cancelOrder($id, \App\Services\OrderService $orderService)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $order = $user->orders()->findOrFail($id);

        if ($order->status !== Order::STATUS_PENDING) {
            return redirect()->back()->with('error', 'Orders can only be cancelled when the status is Pending.');
        }

        try {
            $orderService->updateOrderStatus($order, Order::STATUS_CANCELLED, $user, 'Customer cancelled order themselves');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Order cancelled successfully!');
    }

    public function returnOrder($id, \App\Services\OrderService $orderService)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $order = $user->orders()->findOrFail($id);

        if (!in_array($order->status, [Order::STATUS_COMPLETED, Order::STATUS_SHIPPED])) {
            return redirect()->back()->with('error', 'Chỉ có thể hoàn hàng cho đơn hàng đã giao hoặc hoàn thành.');
        }

        try {
            $orderService->updateOrderStatus($order, Order::STATUS_RETURNED, $user, 'Khách hàng yêu cầu hoàn hàng');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Yêu cầu hoàn hàng đã được ghi nhận!');
    }

    // ===== USER BANK ACCOUNTS =====

    public function storeBankAccount(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'bank_name'      => 'required|string|max:100',
            'bank_id'        => 'required|string|max:50',
            'account_number' => 'required|string|max:100',
            'account_name'   => 'required|string|max:255',
        ]);

        if ($request->boolean('is_default')) {
            $user->bankAccounts()->update(['is_default' => false]);
        }

        $user->bankAccounts()->create([
            'bank_name'      => $request->bank_name,
            'bank_id'        => $request->bank_id,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
            'is_default'     => $request->boolean('is_default'),
        ]);

        return redirect()->back()->with('success', 'Thêm tài khoản ngân hàng thành công!');
    }

    public function updateBankAccount(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $bank = $user->bankAccounts()->findOrFail($id);

        $request->validate([
            'bank_name'      => 'required|string|max:100',
            'bank_id'        => 'required|string|max:50',
            'account_number' => 'required|string|max:100',
            'account_name'   => 'required|string|max:255',
        ]);

        if ($request->boolean('is_default')) {
            $user->bankAccounts()->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $bank->update([
            'bank_name'      => $request->bank_name,
            'bank_id'        => $request->bank_id,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
            'is_default'     => $request->boolean('is_default'),
        ]);

        return redirect()->back()->with('success', 'Cập nhật tài khoản ngân hàng thành công!');
    }

    public function destroyBankAccount($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->bankAccounts()->findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Đã xóa tài khoản ngân hàng.');
    }
}
