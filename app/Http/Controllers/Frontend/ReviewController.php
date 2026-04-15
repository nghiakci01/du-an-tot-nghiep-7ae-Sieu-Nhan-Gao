<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use App\Notifications\NewReviewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ReviewController extends Controller
{
    public function store(\App\Http\Requests\Generated\ReviewRequest $request, $productId)
    {
        if (! Auth::check()) {
            return back()->with('error', 'Vui lòng đăng nhập để gửi đánh giá.');
        }

        // Chỉ cho phép review nếu đã mua và nhận hàng thành công
        $hasPurchased = Order::where('user_id', Auth::id())
            ->where('status', Order::STATUS_COMPLETED)
            ->whereHas('items', fn ($q) => $q->where('product_id', $productId))
            ->exists();

        if (! $hasPurchased) {
            return back()->with('error', 'Bạn cần mua và nhận sản phẩm này trước khi có thể đánh giá.');
        }

        // Mỗi user chỉ được review 1 lần
        $existingReview = Review::where('product_id', $productId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        // Validation handled by ReviewRequest

        $review = Review::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Notify Admins
        $admins = User::getAdmins();
        Notification::send($admins, new NewReviewNotification($review));

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}
