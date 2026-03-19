<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\UserBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewOrderReturnRequestNotification;

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

    public function returnOrderForm($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $order = $user->orders()->findOrFail($id);

        if (!in_array($order->status, [Order::STATUS_COMPLETED, Order::STATUS_SHIPPED])) {
            return redirect()->back()->with('error', 'Chỉ có thể yêu cầu hoàn hàng cho đơn hàng đã giao hoặc hoàn thành.');
        }

        if ($order->returnRequest) {
            return redirect()->route('account.orders.show', $order->id)->with('info', 'Đơn hàng này đã có yêu cầu hoàn trả.');
        }

        return view('frontend.account.orders.return_form', compact('order'));
    }

    public function submitReturnRequest(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $order = $user->orders()->findOrFail($id);

        if (!in_array($order->status, [Order::STATUS_COMPLETED, Order::STATUS_SHIPPED])) {
            return redirect()->back()->with('error', 'Chỉ có thể yêu cầu hoàn hàng cho đơn hàng đã giao hoặc hoàn thành.');
        }

        if ($order->returnRequest) {
            return redirect()->back()->with('error', 'Đơn hàng này đã có yêu cầu hoàn trả.');
        }

        $request->validate([
            'reason' => 'required|string|max:255',
            'note' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('returns', 'public');
                $imagePaths[] = $path;
            }
        }

        $returnRequest = \App\Models\OrderReturnRequest::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'reason' => $request->reason,
            'note' => $request->note,
            'images' => $imagePaths,
            'refund_amount' => $order->final_total,
            'status' => 'pending',
        ]);

        // Thông báo cho các Admin
        $admins = \App\Models\User::getAdmins();
        Notification::send($admins, new NewOrderReturnRequestNotification($returnRequest));

        return redirect()->route('account.orders.show', $order->id)
            ->with('success', 'Yêu cầu hoàn trả của bạn đã được gửi và đang chờ xử lý.');
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

    /**
     * Khách hàng nộp thông tin vận chuyển khi hàng hoàn đã được Duyệt (Approved)
     */
    public function submitShipping(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        $returnRequest = $order->returnRequest;

        if (!$returnRequest || $returnRequest->status !== 'approved') {
            return redirect()->back()->with('error', 'Yêu cầu trả hàng không ở trạng thái được phép gửi hàng.');
        }

        $request->validate([
            'shipping_info' => 'required|string|max:1000',
            'shipping_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'shipping_info.required' => 'Vui lòng nhập thông tin vận chuyển (Mã vận đơn, đơn vị vận chuyển...).',
            'shipping_proof.required' => 'Vui lòng tải lên ảnh minh chứng đã gửi hàng.',
        ]);

        $data = [
            'shipping_info' => $request->shipping_info,
            'status' => 'shipping', // Chuyển sang trạng thái Đang gửi hàng
        ];

        if ($request->hasFile('shipping_proof')) {
            $image = $request->file('shipping_proof');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            \Illuminate\Support\Facades\File::ensureDirectoryExists(public_path('uploads/returns'));
            $image->move(public_path('uploads/returns'), $imageName);
            $data['shipping_proof'] = 'uploads/returns/' . $imageName;
        }

        $returnRequest->update($data);

        // Ghi lịch sử
        if (class_exists(\App\Models\OrderHistory::class)) {
            \App\Models\OrderHistory::create([
                'order_id' => $order->id,
                'previous_status' => $order->status,
                'new_status' => $order->status,
                'note' => 'Khách hàng đã nộp thông tin vận chuyển hàng hoàn: ' . $request->shipping_info,
                'user_id' => Auth::id()
            ]);
        }

        return redirect()->back()->with('success', 'Gửi thông tin vận chuyển thành công. Chúng tôi sẽ thông báo khi nhận được hàng.');
    }
}
