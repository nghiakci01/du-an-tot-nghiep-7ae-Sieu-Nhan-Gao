<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Báo cáo doanh thu</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #2c3e50; margin-bottom: 5px; }
        .info { margin-bottom: 20px; }
        .stats-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .stats-table th, .stats-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .stats-table th { background-color: #f8f9fa; font-weight: bold; }
        .section-title { background-color: #2c3e50; color: white; padding: 8px 15px; margin-bottom: 15px; }
        .product-table { width: 100%; border-collapse: collapse; }
        .product-table th, .product-table td { border-bottom: 1px solid #eee; padding: 8px; text-align: left; }
        .product-table th { color: #7f8c8d; font-size: 10px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #95a5a6; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BÁO CÁO DOANH THU</h1>
        <p>Elite Fashion Store - Bản tin quản trị</p>
    </div>

    <div class="info">
        <p><strong>Thời gian báo cáo:</strong> Từ {{ $startDate }} đến {{ $endDate }}</p>
        <p><strong>Ngày tạo báo cáo:</strong> {{ $generatedAt }}</p>
    </div>

    <div class="section-title">TỔNG QUAN HỢP NHẤT</div>
    <table class="stats-table">
        <tr>
            <th>Chỉ số</th>
            <th>Giá trị</th>
        </tr>
        <tr>
            <td>Tổng doanh thu</td>
            <td class="text-right"><strong>{{ number_format($stats['total_revenue']) }} VND</strong></td>
        </tr>
        <tr>
            <td>Tổng số đơn hàng</td>
            <td class="text-right">{{ number_format($stats['total_orders']) }} đơn</td>
        </tr>
        <tr>
            <td>Giá trị trung bình đơn (AOV)</td>
            <td class="text-right">
                {{ $stats['total_orders'] > 0 ? number_format($stats['total_revenue'] / $stats['total_orders']) : 0 }} VND
            </td>
        </tr>
        <tr>
            <td>Khách hàng mới</td>
            <td class="text-right">{{ number_format($stats['total_customers']) }} người</td>
        </tr>
    </table>

    <div class="section-title">TOP 10 SẢN PHẨM BÁN CHẠY</div>
    <table class="product-table">
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th class="text-right">Giá bán</th>
                <th class="text-right">Đã bán</th>
                <th class="text-right">Doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProducts as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td class="text-right">{{ number_format($product->price) }} đ</td>
                <td class="text-right">{{ $product->total_sold }}</td>
                <td class="text-right">{{ number_format($product->price * $product->total_sold) }} đ</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Trang 1/1 - Elite Fashion Store - Hệ thống quản trị tự động
    </div>
</body>
</html>
