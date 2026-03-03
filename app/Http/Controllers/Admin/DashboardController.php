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
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);
    }
}
