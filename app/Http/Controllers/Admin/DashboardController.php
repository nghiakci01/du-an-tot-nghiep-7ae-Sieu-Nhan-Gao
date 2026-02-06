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
        $totalRevenue = Order::where('status', 'COMPLETED')->sum('total_price');

        // 2. Tổng số đơn hàng
        $totalOrders = Order::count();

        // 3. Đơn hàng mới (Pending)
        $newOrders = Order::where('status', 'PENDING')->count();

        // 4. Tổng số khách hàng (User có role là user hoặc user thường)
        // Giả sử toàn bộ trong bảng users là khách hàng trừ admin, hoặc đếm tất cả nếu chưa phân role kỹ
        $totalCustomers = User::count();

        // 5. Tổng số sản phẩm đang bán
        $totalProducts = Product::where('is_active', true)->count();

        // 6. Sản phẩm sắp hết hàng (Stock < 10)
        // Cần join với variants để tính tổng stock
        // Hoặc đơn giản là đếm variants sắp hết hàng
        $lowStockProducts = \App\Models\ProductVariant::where('stock_quantity', '<', 10)->count();

        // 7. Lấy 5 đơn hàng mới nhất
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'newOrders',
            'totalCustomers',
            'totalProducts',
            'lowStockProducts',
            'recentOrders'
        ));
    }
}
