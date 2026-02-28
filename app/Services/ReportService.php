<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportService
{
    /**
     * Get overview statistics for a date range
     */
    public function getOverviewStats($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: now()->subDays(30)->startOfDay();
        $endDate = $endDate ?: now()->endOfDay();

        $totalRevenue = Order::where('status', Order::STATUS_COMPLETED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        
        $newOrders = Order::where('status', Order::STATUS_PENDING)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'new_orders' => $newOrders,
            'total_customers' => $totalCustomers,
            'total_products' => Product::where('is_active', true)->count(),
            'low_stock_products' => ProductVariant::where('stock_quantity', '<', 10)->count(),
        ];
    }

    /**
     * Get revenue data for chart
     */
    public function getRevenueChartData($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: now()->subDays(30)->startOfDay();
        $endDate = $endDate ?: now()->endOfDay();

        $revenueData = Order::where('status', Order::STATUS_COMPLETED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return [
            'labels' => $revenueData->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d/m'))->toArray(),
            'values' => $revenueData->pluck('total')->toArray(),
        ];
    }

    /**
     * Get order status distribution
     */
    public function getOrderStatusData($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: now()->subDays(30)->startOfDay();
        $endDate = $endDate ?: now()->endOfDay();

        $data = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $labels = array_map(function ($status) {
            $order = new Order(['status' => $status]);
            return $order->status_text;
        }, array_keys($data));

        return [
            'labels' => $labels,
            'values' => array_values($data),
        ];
    }

    /**
     * Get top selling products
     */
    public function getTopProducts($startDate = null, $endDate = null, $limit = 5)
    {
        $startDate = $startDate ?: now()->subDays(30)->startOfDay();
        $endDate = $endDate ?: now()->endOfDay();

        return DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->where('orders.status', Order::STATUS_COMPLETED)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.name',
                'products.price',
                'products.image',
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            ->groupBy('products.id', 'products.name', 'products.price', 'products.image')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }
}
