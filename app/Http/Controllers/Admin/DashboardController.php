<?php

// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ConversionTrackingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $reportService;

    public function __construct(\App\Services\ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $preset = $request->input('preset', 'last_30_days');
        
        // Mặc định cho Tùy chỉnh nếu có start_date và end_date
        if ($preset === 'custom' || ($request->has('start_date') && $request->has('end_date') && !$request->has('preset'))) {
            $preset = 'custom';
            $startDate = $request->start_date ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : now()->subDays(30)->startOfDay();
            $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();
        } else {
            // Xử lý các Preset
            switch ($preset) {
                case 'today':
                    $startDate = now()->startOfDay();
                    $endDate = now()->endOfDay();
                    break;
                case 'this_week':
                    $startDate = now()->startOfWeek();
                    $endDate = now()->endOfWeek();
                    break;
                case 'this_month':
                    $startDate = now()->startOfMonth();
                    $endDate = now()->endOfMonth();
                    break;
                case 'this_quarter':
                    $startDate = now()->startOfQuarter();
                    $endDate = now()->endOfQuarter();
                    break;
                case 'this_year':
                    $startDate = now()->startOfYear();
                    $endDate = now()->endOfYear();
                    break;
                case 'last_7_days':
                    $startDate = now()->subDays(7)->startOfDay();
                    $endDate = now()->endOfDay();
                    break;
                case 'last_30_days':
                default:
                    $preset = 'last_30_days'; // Đảm bảo fallback về đúng key
                    $startDate = now()->subDays(30)->startOfDay();
                    $endDate = now()->endOfDay();
                    break;
            }
        }

        $stats = $this->reportService->getOverviewStats($startDate, $endDate);
        $revenueChart = $this->reportService->getRevenueChartData($startDate, $endDate);
        $halfYearChart = $this->reportService->getHalfYearComparisonData(now()->year);
        $orderStatus = $this->reportService->getOrderStatusData($startDate, $endDate);
        $topProducts = $this->reportService->getTopProducts($startDate, $endDate);

        // Tính tổng số lượng sản phẩm đã bán trong kỳ để làm thanh tiến trình
        $totalProductsSold = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', Order::STATUS_COMPLETED)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->sum('order_items.quantity');

        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // Get Top Wishlisted Products
        $topWishlisted = \App\Models\Product::withCount('wishlistedBy')
            ->orderByDesc('wishlisted_by_count')
            ->take(10)
            ->get();

        // Get Best Selling Products (Top Sold)
        $bestSellers = $this->reportService->getTopProducts($startDate, $endDate, 10);

        // Get Low Stock List
        $lowStockList = \App\Models\ProductVariant::with(['product', 'sizeRelationship', 'colorRelationship'])
            ->whereColumn('stock_quantity', '<=', 'alert_threshold')
            ->orderBy('stock_quantity', 'asc')
            ->take(10)
            ->get();

        return view('admin.dashboard', [
            'totalRevenue' => $stats['total_revenue'],
            'totalProfit' => $stats['total_profit'],
            'totalOrders' => $stats['total_orders'],
            'newOrders' => $stats['new_orders'],
            'totalCustomers' => $stats['total_customers'],
            'totalProducts' => $stats['total_products'],
            'lowStockProducts' => $stats['low_stock_products'],

            'recentOrders' => $recentOrders,
            'revenueLabels' => $revenueChart['labels'],
            'revenueValues' => $revenueChart['values'],
            'statusLabels' => $orderStatus['labels'],
            'statusValues' => $orderStatus['values'],
            'halfYearChart' => $halfYearChart,
            'topProducts' => $topProducts,
            'totalProductsSold' => $totalProductsSold > 0 ? $totalProductsSold : 1, // Tránh chia cho 0
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'preset' => $preset,
            'topWishlisted' => $topWishlisted,
            'bestSellers' => $bestSellers,
            'lowStockList' => $lowStockList,
        ]);
    }

    /**
     * API Thống kê doanh thu (Trình diễn dạng JSON cho filter Tuần/Tháng)
     */
    public function revenueApi(Request $request)
    {
        $filter = $request->input('filter', 'month');

        if ($filter === 'week') {
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
        } elseif ($filter === 'quarter') {
            $startDate = now()->startOfQuarter();
            $endDate = now()->endOfQuarter();
        } elseif ($filter === 'year') {
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();
        } else {
            // Default is current month
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        }

        // Nếu client muốn custom date
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();
        }

        $overviewStats = $this->reportService->getOverviewStats($startDate, $endDate);
        $revenueChart = $this->reportService->getRevenueChartData($startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => [
                'period' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                    'filter' => $filter
                ],
                'summary' => [
                    'total_revenue' => $overviewStats['total_revenue'],
                    'total_orders' => $overviewStats['total_orders'],
                    'successful_orders' => Order::where('status', Order::STATUS_COMPLETED)->whereBetween('created_at', [$startDate, $endDate])->count(),
                ],
                'chart' => [
                    'labels' => $revenueChart['labels'],
                    'values' => $revenueChart['values'],
                ]
            ]
        ]);
    }
}
