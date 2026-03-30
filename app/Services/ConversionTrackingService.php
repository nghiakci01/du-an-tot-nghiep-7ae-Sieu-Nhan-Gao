<?php

namespace App\Services;

use App\Models\CartAbandonment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ConversionTrackingService
{
    /**
     * Track cart abandonment when user leaves with items in cart.
     * Called via scheduled command or session-based trigger.
     */
    public function trackAbandonment(?int $userId, ?string $sessionId, array $cartData): ?CartAbandonment
    {
        if (empty($cartData)) {
            // Ngôn ngữ e-com: khách tự xóa hết giỏ hàng thì không cần nhắc nhở nữa
            CartAbandonment::where(function ($q) use ($userId, $sessionId) {
                if ($userId) $q->where('user_id', $userId);
                elseif ($sessionId) $q->where('session_id', $sessionId);
            })->where('status', 'abandoned')->delete();

            return null;
        }

        $total = 0;
        $itemCount = 0;
        foreach ($cartData as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
            $itemCount += $item['quantity'] ?? 1;
        }

        $attributes = [];
        if ($userId) {
            $attributes['user_id'] = $userId;
        } else {
            $attributes['session_id'] = $sessionId;
        }

        return CartAbandonment::updateOrCreate(
            $attributes,
            [
                'cart_data' => $cartData,
                'cart_total' => $total,
                'item_count' => $itemCount,
                'status' => 'abandoned',
                'abandoned_at' => now(),
            ]
        );
    }

    /**
     * Mark an abandoned cart as recovered (user came back and added items again).
     */
    public function markRecovered(?int $userId, ?string $sessionId): void
    {
        CartAbandonment::where(function ($q) use ($userId, $sessionId) {
            if ($userId) $q->where('user_id', $userId);
            elseif ($sessionId) $q->where('session_id', $sessionId);
        })
        ->where('status', 'abandoned')
        ->update([
            'status' => 'recovered',
            'recovered_at' => now(),
        ]);
    }

    /**
     * Get conversion funnel stats for admin dashboard.
     */
    public function getFunnelStats(string $period = '30d'): array
    {
        $days = match ($period) {
            '7d' => 7,
            '30d' => 30,
            '90d' => 90,
            default => 30,
        };

        $startDate = now()->subDays($days);

        // Total visitors with items in cart (abandoned + converted)
        $totalCartsCreated = CartAbandonment::where('created_at', '>=', $startDate)->count();
        $abandonedCarts = CartAbandonment::abandoned()->where('created_at', '>=', $startDate)->count();
        $recoveredCarts = CartAbandonment::recovered()->where('created_at', '>=', $startDate)->count();

        // Orders placed
        $ordersPlaced = Order::where('created_at', '>=', $startDate)->count();
        $ordersCompleted = Order::where('created_at', '>=', $startDate)
            ->where('status', 'completed')
            ->count();

        // Revenue (completed orders only - for display)
        $totalRevenue = Order::where('created_at', '>=', $startDate)
            ->where('status', 'completed')
            ->sum('final_total');

        // Average order value: total revenue across ALL orders / total orders placed
        $allOrdersRevenue = Order::where('created_at', '>=', $startDate)
            ->sum('final_total');
        $avgOrderValue = $ordersPlaced > 0 ? $allOrdersRevenue / $ordersPlaced : 0;

        // Abandoned cart value (potential lost revenue)
        $abandonedValue = CartAbandonment::abandoned()
            ->where('created_at', '>=', $startDate)
            ->sum('cart_total');

        // Conversion rate
        $cartToOrderRate = $totalCartsCreated > 0
            ? round(($ordersPlaced / ($totalCartsCreated + $ordersPlaced)) * 100, 1)
            : 0;

        // Daily order trend
        $dailyOrders = Order::where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'), DB::raw('SUM(final_total) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'period' => $period,
            'total_carts_tracked' => $totalCartsCreated,
            'abandoned_carts' => $abandonedCarts,
            'recovered_carts' => $recoveredCarts,
            'orders_placed' => $ordersPlaced,
            'orders_completed' => $ordersCompleted,
            'total_revenue' => $totalRevenue,
            'avg_order_value' => $avgOrderValue,
            'abandoned_value' => $abandonedValue,
            'cart_to_order_rate' => $cartToOrderRate,
            'daily_orders' => $dailyOrders,
        ];
    }
}
