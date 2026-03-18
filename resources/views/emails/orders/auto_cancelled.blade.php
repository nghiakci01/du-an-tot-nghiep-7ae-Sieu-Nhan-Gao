<x-mail::message>
# Đơn hàng #{{ $order->id }} đã tự động bị hủy

Xin chào {{ $order->name }},

Rất tiếc phải thông báo rằng đơn hàng **#{{ $order->id }}** của bạn tại **{{ config('app.name') }}** đã tự động bị hủy do quá thời gian quy định mà hệ thống chưa nhận được thanh toán.

## Lịch sử đơn hàng
- **Mã đơn hàng:** #{{ $order->id }}
- **Ngày đặt:** {{ $order->created_at->format('d/m/Y H:i') }}
- **Tổng giá trị:** {{ number_format($order->final_total) }} đ
- **Phương thức thanh toán:** {{ $order->payment_method }}

Hàng hóa trong đơn này đã được tự động hoàn lại vào kho để phục vụ các khách hàng khác. 
Nếu bạn vẫn có nhu cầu mua các sản phẩm này, vui lòng truy cập website và đặt lại một đơn hàng mới.

<x-mail::button :url="url('/')">
    Quay lại {{ config('app.name') }}
</x-mail::button>

Nếu bạn đã thanh toán nhưng vẫn nhận được email này, vui lòng chụp lại biên lai giao dịch và phản hồi lại email này để chúng tôi hỗ trợ kiểm tra.

Trân trọng,<br>
Đội ngũ {{ config('app.name') }}
</x-mail::message>
