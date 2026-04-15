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

    public function submitReturnRequest(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $order = $user->orders()->findOrFail($id);

        if (!\App\Models\OrderReturnRequest::canBeReturned($order)) {
            return redirect()->back()->with('error', 'Chỉ có thể yêu cầu hoàn hàng cho đơn hàng đã hoàn thành và trong vòng 7 ngày kể từ lúc nhận hàng.');
        }

        if ($order->returnRequest) {
            return redirect()->back()->with('error', 'Đơn hàng này đã có yêu cầu hoàn trả.');
        }

        $request->validate([
            'type' => 'required|in:refund,exchange',
            'reason_type' => 'required|in:wrong_size,disliked,defective,other',
            'reason' => 'required|string|max:255',
            'return_method' => 'required|string|in:at_home,at_post_office',
            'note' => 'required|string|max:1000',
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'videos' => 'required|array|min:1',
            'videos.*' => 'required|file|mimes:mp4,mov,avi,webm|max:51200',
            'items' => 'required|array',
            'items.*.selected' => 'sometimes|boolean',
            'items.*.quantity' => 'sometimes|integer|min:1',
            'bank_name' => 'required_if:type,refund|nullable|string|max:255',
            'bank_bin' => 'required_if:type,refund|nullable|string|max:20',
            'account_number' => 'required_if:type,refund|nullable|string|max:50',
            'account_name' => 'required_if:type,refund|nullable|string|max:255',
        ], [
            'type.required' => 'Vui lòng chọn loại yêu cầu (Hoàn tiền hoặc Đổi hàng).',
            'reason_type.required' => 'Vui lòng chọn lý do cụ thể.',
            'images.required' => 'Vui lòng cung cấp ít nhất một hình ảnh minh chứng.',
            'images.*.image' => 'File tải lên phải là hình ảnh.',
            'note.required' => 'Vui lòng mô tả chi tiết tình trạng sản phẩm hoặc yêu cầu của bạn.',
            'return_method.required' => 'Vui lòng chọn phương thức gửi hàng hoàn trả.',
            'videos.required' => 'Vui lòng cung cấp ít nhất một video minh chứng.',
            'videos.*.mimes' => 'Video phải có định dạng mp4, mov, avi hoặc webm.',
            'bank_name.required_if' => 'Vui lòng chọn ngân hàng nhận tiền hoàn.',
            'account_number.required_if' => 'Vui lòng nhập số tài khoản ngân hàng.',
            'account_name.required_if' => 'Vui lòng nhập tên chủ tài khoản.',
        ]);

        // Kiểm tra bắt buộc minh chứng cho hàng lỗi
        if ($request->reason_type === 'defective') {
            if (!$request->hasFile('images')) {
                return redirect()->back()->with('error', 'Với lý do hàng lỗi, bạn bắt buộc phải tải lên ảnh minh chứng.')->withInput();
            }
            if (!$request->hasFile('videos')) {
                return redirect()->back()->with('error', 'Với lý do hàng lỗi, bạn bắt buộc phải tải lên video minh chứng tình trạng lỗi.')->withInput();
            }
        }

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
                'type' => $request->type,
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
    public function submitShipping(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        $returnRequest = $order->returnRequest;

        if (!$returnRequest || !$returnRequest->isApproved()) {
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
            'status' => \App\Models\OrderReturnRequest::STATUS_SHIPPING_BACK, // Chuyển sang trạng thái Đang gửi hàng về
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

    public function updateReturnMethod(Request $request, $id)
    {
        $request->validate([
            'return_method' => 'required|string|in:at_home,at_post_office',
        ]);

        $order = \App\Models\Order::findOrFail($id);
        if ($order->returnRequest) {
            $order->returnRequest->update([
                'return_method' => $request->return_method
            ]);
        }

        return back()->with('success', 'Đã cập nhật phương thức gửi hàng thành công.');
    }

}
