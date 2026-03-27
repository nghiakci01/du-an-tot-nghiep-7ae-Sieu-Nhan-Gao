<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\UserBankAccount;
use App\Models\BankSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewOrderReturnRequestNotification;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $socialAccounts = collect();
        $orders = collect();
        $wishlists = collect();
        $coupons = collect();
        $userBankAccounts = collect();
        $walletTransactions = collect();
        $walletTopupRequests = collect();
        $walletWithdrawRequests = collect();
        $bankSettings = BankSetting::where('is_active', true)->get();
        $totalOrders = 0;
        $totalSpent = 0;
        $wishCount = 0;

        if ($user) {
            $orders = $user->orders()->latest()->paginate(10);
            $wishCount = $user->wishlists()->count();
            $wishlists = $user->wishlists()->with('product')->get();
            $userBankAccounts = $user->bankAccounts;
            
            $coupons = \App\Models\Coupon::where(function ($q) use ($user) {
                $q->whereNull('user_id')->orWhere('user_id', $user->id);
            })
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->whereRaw('used_count < usage_limit')
                ->get();
            
            $walletTransactions   = $user->walletTransactions()->take(20)->get();
            $walletTopupRequests  = $user->walletTopupRequests()->take(10)->get();
            $walletWithdrawRequests = $user->walletWithdrawRequests()->take(10)->get();

            $totalOrders = $orders->total();
            $totalSpent = $user->orders()->where('status', 'completed')->sum('final_total');
            $socialAccounts = $user->socialAccounts;
        }

        return view('frontend.account.index', compact(
            'user', 'orders', 'coupons', 'wishlists',
            'userBankAccounts', 'walletTransactions', 'walletTopupRequests', 'walletWithdrawRequests',
            'bankSettings', 'totalOrders', 'totalSpent', 'wishCount', 'socialAccounts'
        ));
    }

    public function showOrder($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user) {
            $order = $user->orders()->with(['items.product', 'histories'])->findOrFail($id);
        } else {
            // Guest access verification (Trigger re-scan)
            if (session('verified_order_id') != $id) {
                return redirect()->route('order-tracking.index')
                    ->with('error', 'Vui lòng xác thực thông tin đơn hàng để xem chi tiết.');
            }
            $order = Order::with(['items.product', 'histories'])->findOrFail($id);
        }

        // Lấy danh sách product_id đã được user review trong đơn hàng này
        /** @var \Illuminate\Database\Eloquent\Collection $items */
        $items = $order->items;
        $productIds = $items->pluck('product_id')->filter()->unique();
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

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(storage_path('app/public/avatars'), $avatarName);
            $user->avatar = 'avatars/' . $avatarName;
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
            'videos.*' => 'nullable|file|mimes:mp4,mov,avi,webm|max:51200',
            'items' => 'required|array',
            'items.*.selected' => 'sometimes|boolean',
            'items.*.quantity' => 'sometimes|integer|min:1',
        ]);

        // Filter selected items and calculate refund amount
        $selectedItems = [];
        $totalRefund = 0;
        
        foreach ($request->items as $itemId => $data) {
            if (isset($data['selected']) && $data['selected'] == 1) {
                $orderItem = \App\Models\OrderItem::where('order_id', $order->id)->findOrFail($itemId);
                
                $qty = (int) ($data['quantity'] ?? 1);
                if ($qty > $orderItem->quantity) {
                    return redirect()->back()->with('error', "Số lượng trả của sản phẩm {$orderItem->product_name} vượt quá số lượng đã mua.");
                }

                $selectedItems[] = [
                    'order_item_id' => $itemId,
                    'quantity' => $qty,
                    'price' => $orderItem->price,
                ];
                $totalRefund += $qty * $orderItem->price;
            }
        }

        if (empty($selectedItems)) {
            return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một sản phẩm để hoàn trả.');
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            \Illuminate\Support\Facades\File::ensureDirectoryExists(storage_path('app/public/returns'));
            foreach ($request->file('images') as $image) {
                $name = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(storage_path('app/public/returns'), $name);
                $imagePaths[] = 'returns/' . $name;
            }
        }

        $videoPaths = [];
        if ($request->hasFile('videos')) {
            \Illuminate\Support\Facades\File::ensureDirectoryExists(storage_path('app/public/returns/videos'));
            foreach ($request->file('videos') as $video) {
                $name = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
                $video->move(storage_path('app/public/returns/videos'), $name);
                $videoPaths[] = 'returns/videos/' . $name;
            }
        }

        return \Illuminate\Support\Facades\DB::transaction(function () use ($user, $order, $request, $imagePaths, $videoPaths, $selectedItems, $totalRefund) {
            $returnRequest = \App\Models\OrderReturnRequest::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'reason' => $request->reason,
                'note' => $request->note,
                'images' => $imagePaths,
                'videos' => $videoPaths,
                'refund_amount' => $totalRefund,
                'status' => 'pending',
            ]);

            foreach ($selectedItems as $itemData) {
                $returnRequest->items()->create($itemData);
            }

            // Thông báo cho các Admin
            $admins = \App\Models\User::getAdmins();
            Notification::send($admins, new \App\Notifications\NewOrderReturnRequestNotification($returnRequest));

            return redirect()->route('account.orders.show', $order->id)
                ->with('success', 'Yêu cầu hoàn trả của bạn đã được gửi và đang chờ xử lý.');
        });
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
