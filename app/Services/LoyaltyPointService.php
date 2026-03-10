<?php

namespace App\Services;

use App\Models\LoyaltyPoint;
use App\Models\Order;
use App\Models\User;

class LoyaltyPointService
{
    const POINTS_PER_UNIT = 10000; // 1 point per 10,000 VND

    public function earnPoints(Order $order): ?LoyaltyPoint
    {
        if (!$order->user_id) {
            return null;
        }

        $points = $this->calculatePoints($order->final_total);
        if ($points <= 0) {
            return null;
        }

        return LoyaltyPoint::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'points' => $points,
            'description' => 'Tích điểm đơn hàng #' . $order->id,
        ]);
    }

    public function revokePoints(Order $order): void
    {
        LoyaltyPoint::where('order_id', $order->id)
            ->where('points', '>', 0)
            ->delete();
    }

    public function calculatePoints(float $amount): int
    {
        return (int) floor($amount / self::POINTS_PER_UNIT);
    }

    public function getTotalPoints(int $userId): int
    {
        return (int) LoyaltyPoint::where('user_id', $userId)->sum('points');
    }

    public function redeemPoints(int $userId, int $points, ?string $description = null): ?LoyaltyPoint
    {
        $available = $this->getTotalPoints($userId);
        if ($available < $points || $points <= 0) {
            return null;
        }

        return LoyaltyPoint::create([
            'user_id' => $userId,
            'order_id' => null,
            'points' => -$points,
            'description' => $description ?? 'Quy đổi điểm thưởng',
        ]);
    }

    public function pointsToDiscount(int $points): float
    {
        return $points * 1000; // 1 point = 1,000 VND discount
    }
}
