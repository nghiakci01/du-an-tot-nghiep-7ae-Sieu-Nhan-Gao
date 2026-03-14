<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\VtonHistory;
use App\Models\VtonModel;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            ->sum('final_total');

        $totalProfit = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', Order::STATUS_COMPLETED)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(DB::raw('SUM((order_items.price - order_items.cost_price) * order_items.quantity) as profit'))
            ->first()->profit ?? 0;

        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        $newOrders = Order::where('status', Order::STATUS_PENDING)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return [
            'total_revenue' => $totalRevenue,
            'total_profit' => $totalProfit,
            'total_orders' => $totalOrders,
            'new_orders' => $newOrders,
            'total_customers' => $totalCustomers,
            'total_products' => Product::where('is_active', true)->count(),
            'low_stock_products' => 0, // Stock tracking is removed
            'total_vton_histories' => VtonHistory::count(),
            'vton_enabled_products_count' => Product::whereNotNull('vton_model_id')->count(),
        ];
    }

    /**
     * Get VTON specific statistics
     */
    public function getVtonStats($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: now()->subDays(30)->startOfDay();
        $endDate = $endDate ?: now()->endOfDay();

        $totalTryOns = VtonHistory::whereBetween('created_at', [$startDate, $endDate])->count();
        
        $topVtonProducts = DB::table('vton_histories')
            ->join('products', 'products.id', '=', 'vton_histories.product_id')
            ->whereBetween('vton_histories.created_at', [$startDate, $endDate])
            ->select(
                'products.name',
                'products.image',
                DB::raw('count(*) as try_on_count')
            )
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('try_on_count')
            ->limit(5)
            ->get();

        $vtonEnabledCategories = Category::whereNotNull('vton_model_id')->count();

        return [
            'total_try_ons' => $totalTryOns,
            'top_vton_products' => $topVtonProducts,
            'vton_enabled_categories' => $vtonEnabledCategories,
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
                DB::raw('SUM(final_total) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return [
            'labels' => $revenueData->pluck('date')->map(fn ($d) => Carbon::parse($d)->format('d/m'))->toArray(),
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
