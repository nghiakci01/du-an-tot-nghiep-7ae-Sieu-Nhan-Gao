<?php

// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $filterType = $request->get('filter_type', 'last_30_days');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $month = $request->get('month');

        // Get date range based on filter type
        [$dateFrom, $dateTo] = $this->getDateRange($filterType, $startDate, $endDate, $month);

        // 1. Tổng doanh thu (Chỉ tính các đơn hàng đã hoàn thành trong khoảng thời gian)
        $totalRevenue = Order::where('status', 'COMPLETED')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('total_price');

        // 2. Tổng số đơn hàng trong khoảng thời gian
        $totalOrders = Order::whereBetween('created_at', [$dateFrom, $dateTo])->count();

        // 3. Đơn hàng mới (Pending) trong khoảng thời gian
        $newOrders = Order::where('status', 'PENDING')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        // 4. Tổng số khách hàng đăng ký trong khoảng thời gian
        $totalCustomers = User::where('role', 'user')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        // 5. Tổng số sản phẩm đang bán (không lọc theo thời gian)
        $totalProducts = Product::where('is_active', true)->count();

        // 6. Sản phẩm sắp hết hàng (Stock < 10) (không lọc theo thời gian)
        $lowStockProducts = \App\Models\ProductVariant::where('stock_quantity', '<', 10)->count();

        // 7. Lấy 5 đơn hàng mới nhất trong khoảng thời gian
        $recentOrders = Order::with('user')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->latest()
            ->take(5)
            ->get();

        // --- NEW: Data for Charts ---

        // 8. Doanh thu theo ngày trong khoảng thời gian
        $revenueData = Order::where('status', 'COMPLETED')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Prepare data for ApexCharts (Labels and Series)
        $revenueLabels = $revenueData->pluck('date')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('d/m');
        })->toArray();
        $revenueValues = $revenueData->pluck('total')->toArray();

        // 9. Trạng thái đơn hàng (Pie/Donut Chart) trong khoảng thời gian
        $orderStatusData = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statusLabels = array_keys($orderStatusData);
        $statusValues = array_values($orderStatusData);

        // 10. Top 5 Sản phẩm bán chạy trong khoảng thời gian
        $topProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->where('orders.status', 'COMPLETED')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->select(
                'products.name',
                'products.price',
                'products.image',
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            ->groupBy('products.id', 'products.name', 'products.price', 'products.image')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Get filter display text
        $filterText = $this->getFilterText($filterType, $dateFrom, $dateTo, $month);

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'newOrders',
            'totalCustomers',
            'totalProducts',
            'lowStockProducts',
            'recentOrders',
            'revenueLabels',
            'revenueValues',
            'statusLabels',
            'statusValues',
            'topProducts',
            'filterType',
            'startDate',
            'endDate',
            'month',
            'filterText',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Get date range based on filter type
     */
    private function getDateRange($filterType, $startDate, $endDate, $month)
    {
        $now = Carbon::now();

        switch ($filterType) {
            case 'today':
                return [$now->copy()->startOfDay(), $now->copy()->endOfDay()];

            case 'this_week':
                return [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()];

            case 'this_month':
                return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];

            case 'this_year':
                return [$now->copy()->startOfYear(), $now->copy()->endOfYear()];

            case 'custom':
                if ($startDate && $endDate) {
                    return [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay()
                    ];
                }
                // Fallback to last 30 days if dates not provided
                return [$now->copy()->subDays(30)->startOfDay(), $now->copy()->endOfDay()];

            case 'month':
                if ($month) {
                    $monthDate = Carbon::parse($month . '-01');
                    return [$monthDate->copy()->startOfMonth(), $monthDate->copy()->endOfMonth()];
                }
                // Fallback to current month
                return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];

            case 'last_30_days':
            default:
                return [$now->copy()->subDays(30)->startOfDay(), $now->copy()->endOfDay()];
        }
    }

    /**
     * Get filter display text
     */
    private function getFilterText($filterType, $dateFrom, $dateTo, $month)
    {
        switch ($filterType) {
            case 'today':
                return 'Hôm nay (' . $dateFrom->format('d/m/Y') . ')';
            case 'this_week':
                return 'Tuần này (' . $dateFrom->format('d/m') . ' - ' . $dateTo->format('d/m/Y') . ')';
            case 'this_month':
                return 'Tháng này (' . $dateFrom->format('m/Y') . ')';
            case 'this_year':
                return 'Năm này (' . $dateFrom->format('Y') . ')';
            case 'custom':
                return 'Tùy chỉnh (' . $dateFrom->format('d/m/Y') . ' - ' . $dateTo->format('d/m/Y') . ')';
            case 'month':
                return 'Tháng ' . Carbon::parse($month . '-01')->format('m/Y');
            case 'last_30_days':
            default:
                return '30 ngày gần nhất';
        }
    }
}
