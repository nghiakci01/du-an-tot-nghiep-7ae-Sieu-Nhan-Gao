<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Giỏ hàng của bạn đang chờ bạn</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; }
        .header { background: #f8931d; color: white; padding: 10px 20px; text-align: center; }
        .content { padding: 20px; }
        .product-list { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .product-list th, .product-list td { text-align: left; padding: 10px; border-bottom: 1px solid #ddd; }
        .btn { display: inline-block; padding: 10px 20px; background: #f8931d; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; text-align: center; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Elite Shop</h2>
        </div>
        <div class="content">
            <p>Chào {{ rtrim($cartAbandonment->user->name ?? 'bạn') }},</p>
            <p>Chúng tôi nhận thấy bạn vẫn còn {{ $cartAbandonment->item_count }} sản phẩm trong giỏ hàng tại Elite Shop nhưng chưa hoàn tất thanh toán.</p>
            <p>Dưới đây là các sản phẩm bạn đã chọn:</p>
            
            <table class="product-list">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
                @php $cartData = is_string($cartAbandonment->cart_data) ? json_decode($cartAbandonment->cart_data, true) : $cartAbandonment->cart_data; @endphp
                @if(is_array($cartData))
                    @foreach($cartData as $item)
                    <tr>
                        <td>{{ $item['name'] ?? 'Sản phẩm' }}</td>
                        <td>{{ $item['quantity'] ?? 1 }}</td>
                        <td>{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }} ₫</td>
                    </tr>
                    @endforeach
                @endif
            </table>
            
            <p><strong>Tổng cộng: {{ number_format($cartAbandonment->cart_total, 0, ',', '.') }} ₫</strong></p>
            
            <center>
                <a href="{{ url('/cart') }}" class="btn">Tiếp tục thanh toán ngay</a>
            </center>
            
            <p style="margin-top: 20px;">Nếu bạn cần hỗ trợ thêm, vui lòng liên hệ với chúng tôi qua số điện thoại HOTLINE: <strong>0123.456.789</strong>.</p>
            <p>Cảm ơn bạn đã tin tưởng Elite Shop!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Elite Shop. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
