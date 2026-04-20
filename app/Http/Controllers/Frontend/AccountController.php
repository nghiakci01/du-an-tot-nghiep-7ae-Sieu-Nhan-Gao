<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
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
        $data = $this->getDashboardData($user);

        return view('frontend.account.index', array_merge(['user' => $user], $data));
    }

    /**
     * Gathers all necessary data for the account dashboard.
     */
    private function getDashboardData($user): array
    {
        $data = [
            'socialAccounts' => collect(),
            'orders' => collect(),
            'wishlists' => collect(),
            'coupons' => collect(),
            'addresses' => collect(),
            'walletTransactions' => collect(),
            'walletTopupRequests' => collect(),
            'walletWithdrawRequests' => collect(),
            'bankSettings' => collect(),
            'totalOrders' => 0,
            'totalSpent' => 0,
            'wishCount' => 0,
            'notifications' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20),
        ];

        if ($user) {
            $data['orders'] = $user->orders()->latest()->paginate(10);
            $data['wishCount'] = $user->wishlists()->count();
            $data['wishlists'] = $user->wishlists()->with('product')->get();
            $data['addresses'] = $user->addresses()->get();

            // 2. Mã đã nhận (claimed) hoặc được gán riêng (user_id) - bao gồm cả mã đã dùng
            $personalCoupons = $user->claimedCoupons()
                ->where('is_active', true)
                ->get();

            $usedCouponIds = $personalCoupons->whereNotNull('pivot.used_at')->pluck('id')->toArray();

            // 1. Mã công khai (không gán user_id) và chưa từng được user này sử dụng
            $publicCoupons = \App\Models\Coupon::whereNull('user_id')
                ->whereNotIn('id', $usedCouponIds)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->whereRaw('used_count < usage_limit')
                ->get();

            // Gộp lại và ưu tiên mã cá nhân (vì có dữ liệu pivot used_at)
            $data['coupons'] = $personalCoupons->concat($publicCoupons)->unique('id');

            if (config('features.wallet')) {
                $data['walletTransactions'] = $user->walletTransactions()->take(20)->get();
                $data['walletTopupRequests'] = $user->walletTopupRequests()->take(10)->get();
                $data['walletWithdrawRequests'] = $user->walletWithdrawRequests()->take(10)->get();
            }

            $data['totalOrders'] = $data['orders']->total();
            $data['totalSpent'] = $user->orders()->where('status', 'completed')->sum('final_total');
            $data['socialAccounts'] = $user->socialAccounts;

            // Notifications pagination
            $data['notifications'] = $user->notifications()->latest()->paginate(20, ['*'], 'notifications_page');
        }

        return $data;
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

    public function update(\App\Http\Requests\Generated\AccountUpdateRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['nullable', 'string', 'regex:/^(03|05|07|08|09)\d{8}$/'],
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'required_with:new_password|nullable',
            'new_password' => 'nullable|min:8|confirmed',
            'bank_name' => 'nullable|string|max:255',
            'bank_bin' => 'nullable|string|max:20',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:255',
        ], [
            'phone.regex' => 'Số điện thoại phải bắt đầu bằng 03, 05, 07, 08 hoặc 09 và có đúng 10 chữ số.',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->bank_name = $request->bank_name;
        $user->bank_bin = $request->bank_bin;
        $user->account_number = $request->account_number;
        $user->account_name = $request->account_name;

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(storage_path('app/public/avatars'), $avatarName);
            $user->avatar = 'avatars/' . $avatarName;
        }

        if (!empty($request->validated()['new_password'] ?? null)) {
            if (! Hash::check($request->validated()['current_password'] ?? '', $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $user->password = Hash::make($request->validated()['new_password']);
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

            // Khôi phục lại giỏ hàng cho khách
            app(\App\Services\CartService::class)->restoreOrderToCart($order);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Đơn hàng đã được hủy thành công. Các sản phẩm đã được hoàn lại vào giỏ hàng của bạn.');
    }

    public function returnOrderForm($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $order = $user->orders()->findOrFail($id);

        if (!\App\Models\OrderReturnRequest::canBeReturned($order)) {
            return redirect()->back()->with('error', 'Chỉ có thể yêu cầu hoàn hàng cho đơn hàng đã hoàn thành và trong vòng 7 ngày kể từ lúc nhận hàng.');
        }

        if ($order->returnRequest) {
            return redirect()->route('account.orders.show', $order->id)->with('info', 'Đơn hàng này đã có yêu cầu hoàn trả.');
        }

        return view('frontend.account.orders.return_form', compact('order'));
    }

    public function submitReturnRequest(\App\Http\Requests\Generated\ReturnRequest $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $order = $user->orders()->findOrFail($id);

        if (!\App\Models\OrderReturnRequest::canBeReturned($order)) {
            return redirect()->back()->with('error', 'Chỉ có thể yêu cầu hoàn hàng cho đơn hàng đã hoàn thành và trong vòng ' . config('services.return.window_days', 7) . ' ngày kể từ lúc nhận hàng.');
        }

        // Kiểm tra giới hạn số lượng trả hàng (tối đa 3 lần/tháng)
        $monthlyReturns = $user->returnRequests()
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();
        if ($monthlyReturns >= 3) {
            return redirect()->back()->with('error', 'Bạn đã đạt giới hạn 3 yêu cầu trả hàng trong tháng này.');
        }

        if ($order->returnRequest) {
            return redirect()->back()->with('error', 'Đơn hàng này đã có yêu cầu hoàn trả.');
        }

        $validated = $request->validated();

        // Kiểm tra bắt buộc minh chứng cho hàng lỗi
        if ($request->reason_type === 'defective') {
            if (!$request->hasFile('images')) {
                return redirect()->back()->with('error', 'Với lý do hàng lỗi, bạn bắt buộc phải tải lên ảnh minh chứng.')->withInput();
            }
            if (!$request->hasFile('videos')) {
                return redirect()->back()->with('error', 'Với lý do hàng lỗi, bạn bắt buộc phải tải lên video minh chứng tình trạng lỗi.')->withInput();
            }
        }

        // Force return all items strategy
        $selectedItems = [];
        $totalRefund = 0;

        foreach ($order->items as $item) {
            $selectedItems[] = [
                'order_item_id' => $item->id,
                'quantity' => $item->quantity, // Return full quantity
                'price' => $item->price,
            ];
            $totalRefund += $item->quantity * $item->price;
        }

        if (empty($selectedItems)) {
            return redirect()->back()->with('error', 'Đơn hàng này không có sản phẩm để hoàn trả.');
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
                'type' => 'refund',
                'reason_type' => $request->reason_type,
                'reason' => $request->reason,
                'return_method' => $request->return_method,
                'note' => $request->note,
                'images' => $imagePaths,
                'videos' => $videoPaths,
                'refund_amount' => $totalRefund,
                'bank_name' => $request->bank_name,
                'bank_bin' => $request->bank_bin,
                'account_number' => $request->account_number,
                'account_name' => $request->account_name,
                'status' => \App\Models\OrderReturnRequest::STATUS_PENDING,
            ]);

            foreach ($selectedItems as $itemData) {
                $returnRequest->items()->create($itemData);
            }

            // Thông báo cho các Admin
            $admins = \App\Models\User::getAdmins();
            Notification::send($admins, new NewOrderReturnRequestNotification($returnRequest));

            return redirect()->route('account.orders.show', $order->id)
                ->with('success', 'Yêu cầu hoàn trả của bạn đã được gửi và đang chờ xử lý.');
        });
    }


    /**
     * Khách hàng nộp thông tin vận chuyển khi hàng hoàn đã được Duyệt (Approved)
     */
    public function submitShipping(\App\Http\Requests\Generated\ReturnShippingRequest $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        $returnRequest = $order->returnRequest;

        if (!$returnRequest || !$returnRequest->isApproved()) {
            return redirect()->back()->with('error', 'Yêu cầu trả hàng không ở trạng thái được phép gửi hàng.');
        }

        $validated = $request->validated();

        $data = [
            'shipping_info' => $validated['shipping_info'],
            'status' => \App\Models\OrderReturnRequest::STATUS_SHIPPING_BACK,
        ];

        $shippingProofs = [];
        if ($request->hasFile('shipping_proof')) {
            \Illuminate\Support\Facades\File::ensureDirectoryExists(public_path('uploads/returns'));
            foreach ($request->file('shipping_proof') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/returns'), $imageName);
                $shippingProofs[] = 'uploads/returns/' . $imageName;
            }
            $data['shipping_proof'] = $shippingProofs;
        }

        $videoProofs = [];
        if ($request->hasFile('shipping_video')) {
            \Illuminate\Support\Facades\File::ensureDirectoryExists(public_path('uploads/returns/videos'));
            foreach ($request->file('shipping_video') as $video) {
                $videoName = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
                $video->move(public_path('uploads/returns/videos'), $videoName);
                $videoProofs[] = 'uploads/returns/videos/' . $videoName;
            }
            $data['video_proof'] = $videoProofs;
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

    public function updateReturnMethod(\App\Http\Requests\Generated\ReturnMethodRequest $request, $id)
    {
        $validated = $request->validated();

        $order = \App\Models\Order::findOrFail($id);
        if ($order->returnRequest) {
            $order->returnRequest->update([
                'return_method' => $validated['return_method']
            ]);
        }

        return back()->with('success', 'Đã cập nhật phương thức gửi hàng thành công.');
    }

}
