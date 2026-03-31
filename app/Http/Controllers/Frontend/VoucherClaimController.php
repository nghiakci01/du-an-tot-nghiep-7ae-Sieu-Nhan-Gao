<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoucherClaimController extends Controller
{
    public function claim(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'message' => 'Vui lòng đăng nhập để lấy mã giảm giá.',
            ], 401);
        }

        $request->validate([
            'coupon_id' => 'required|exists:coupons,id',
            'source' => 'nullable|string|max:20',
            'source_id' => 'nullable|exists:posts,id',
        ]);

        $coupon = Coupon::findOrFail($request->coupon_id);

        if (!$coupon->isValid()) {
            $message = 'Mã giảm giá không còn hiệu lực.';
            if ($coupon->isExpired()) {
                $message = 'Mã giảm giá đã hết hạn.';
            } elseif ($coupon->hasReachedUsageLimit()) {
                $message = 'Mã giảm giá đã hết lượt sử dụng.';
            }

            return response()->json([
                'success' => false,
                'message' => $message,
                'exhausted' => $coupon->hasReachedUsageLimit(),
            ], 422);
        }

        $user = Auth::user();

        if ($coupon->isClaimedBy($user)) {
            return response()->json([
                'success' => false,
                'already_claimed' => true,
                'message' => 'Bạn đã lấy mã này rồi.',
                'coupon_code' => $coupon->code,
            ], 409);
        }

        DB::transaction(function () use ($coupon, $user, $request) {
            $coupon->claimedByUsers()->attach($user->id, [
                'claimed_at' => now(),
                'source' => $request->input('source', 'news'),
                'source_id' => $request->source_id,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu mã giảm giá vào tài khoản!',
            'coupon_code' => $coupon->code,
            'coupon_value' => $coupon->getFormattedValue(),
            'coupon_description' => $coupon->description,
        ]);
    }
}
