<?php

// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Tổng doanh thu (Chỉ tính các đơn hàng đã hoàn thành)
        $totalRevenue = Order::where('status', Order::STATUS_COMPLETED)->sum('total_price');

        // 2. Tổng số đơn hàng
        $totalOrders = Order::count();

        // 3. Đơn hàng mới (Pending)
        $newOrders = Order::where('status', Order::STATUS_PENDING)->count();

        // 4. Tổng số khách hàng
        $totalCustomers = User::where('role', 'customer')->count();

        // 5. Tổng số sản phẩm đang bán
        $totalProducts = Product::where('is_active', true)->count();

        // 6. Sản phẩm sắp hết hàng (Stock < 10)
        $lowStockProducts = \App\Models\ProductVariant::where('stock_quantity', '<', 10)->count();

        // 7. Lấy 5 đơn hàng mới nhất
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // --- NEW: Data for Charts ---

        // 8. Doanh thu 30 ngày gần nhất
        $revenueData = Order::where('status', Order::STATUS_COMPLETED)
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc') // Order by date ascending for the chart
            ->get();

        // Prepare data for ApexCharts (Labels and Series)
        $revenueLabels = $revenueData->pluck('date')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('d/m');
        })->toArray();
        $revenueValues = $revenueData->pluck('total')->toArray();

        // 9. Trạng thái đơn hàng (Pie/Donut Chart)
        $orderStatusData = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Ensure all statuses are present for consistent coloring if needed, or just send keys/values
        $statusLabels = array_map(function ($status) {
            $order = new Order(['status' => $status]);
            return $order->status_text;
        }, array_keys($orderStatusData));
        $statusValues = array_values($orderStatusData);

        // 10. Top 5 Sản phẩm bán chạy
        $topProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->where('orders.status', Order::STATUS_COMPLETED)
            ->select(
                'products.name',
                'products.price', // Or aggregate sum(order_items.price) if price changes
                'products.image',
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            ->groupBy('products.id', 'products.name', 'products.price', 'products.image')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

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
            'topProducts'
        ));
    }
}
