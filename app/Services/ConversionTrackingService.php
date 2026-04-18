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
    public function getFunnelStats($startDate = null, $endDate = null): array
    {
        $startDate = $startDate ?: now()->subDays(30)->startOfDay();
        $endDate = $endDate ?: now()->endOfDay();

        // Total visitors with items in cart (abandoned + converted)
        $totalCartsCreated = CartAbandonment::whereBetween('created_at', [$startDate, $endDate])->count();
        $abandonedCarts = CartAbandonment::abandoned()->whereBetween('created_at', [$startDate, $endDate])->count();
        $recoveredCarts = CartAbandonment::recovered()->whereBetween('created_at', [$startDate, $endDate])->count();

        // Orders placed
        $ordersPlaced = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $ordersCompleted = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', Order::STATUS_COMPLETED)
            ->count();

        // Revenue (completed orders only - for display)
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', Order::STATUS_COMPLETED)
            ->sum('final_total');

        // Average order value: total revenue across ALL orders / total orders placed
        $allOrdersRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->sum('final_total');
        $avgOrderValue = $ordersPlaced > 0 ? $allOrdersRevenue / $ordersPlaced : 0;

        // Abandoned cart value (potential lost revenue)
        $abandonedValue = CartAbandonment::abandoned()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('cart_total');

        // Conversion rate
        $cartToOrderRate = ($totalCartsCreated + $ordersPlaced) > 0
            ? round(($ordersPlaced / ($totalCartsCreated + $ordersPlaced)) * 100, 1)
            : 0;

        // Daily order trend
        $dailyOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'), DB::raw('SUM(final_total) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
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
            'funnel_steps' => [
                'step1_add_to_cart' => $totalCartsCreated + $ordersPlaced,
                'step2_checkout' => $ordersPlaced,
                'step3_purchase' => $ordersCompleted,
            ]
        ];
    }
}
