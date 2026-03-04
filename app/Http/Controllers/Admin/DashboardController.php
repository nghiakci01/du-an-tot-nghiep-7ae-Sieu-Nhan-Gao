<?php

// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $reportService;

    public function __construct(\App\Services\ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $startDate = $request->start_date ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : now()->subDays(30)->startOfDay();
        $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

        $stats = $this->reportService->getOverviewStats($startDate, $endDate);
        $revenueChart = $this->reportService->getRevenueChartData($startDate, $endDate);
        $orderStatus = $this->reportService->getOrderStatusData($startDate, $endDate);
        $topProducts = $this->reportService->getTopProducts($startDate, $endDate);

        // Tính tổng số lượng sản phẩm đã bán trong kỳ để làm thanh tiến trình
        $totalProductsSold = \DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', \App\Models\Order::STATUS_COMPLETED)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->sum('order_items.quantity');

        $recentOrders = Order::with('user')->latest()->take(5)->get();

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
            'topProducts' => $topProducts,
            'totalProductsSold' => $totalProductsSold > 0 ? $totalProductsSold : 1, // Tránh chia cho 0
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);
    }

    /**
     * API Thống kê doanh thu (Trình diễn dạng JSON cho filter Tuần/Tháng)
     */
    public function revenueApi(Request $request)
    {
        $filter = $request->get('filter', 'month');

        if ($filter === 'week') {
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
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
                    'successful_orders' => \App\Models\Order::where('status', \App\Models\Order::STATUS_COMPLETED)->whereBetween('created_at', [$startDate, $endDate])->count(),
                ],
                'chart' => [
                    'labels' => $revenueChart['labels'],
                    'values' => $revenueChart['values'],
                ]
            ]
        ]);
    }
}
