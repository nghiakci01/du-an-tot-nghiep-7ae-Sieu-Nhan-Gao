<x-mail::message>
@if($reminderStep === 1)
# Nhắc nhở thanh toán lần 1: Đơn hàng #{{ $order->id }}
@else
# YÊU CẦU THANH TOÁN GẤP: Đơn hàng #{{ $order->id }}
@endif

Xin chào {{ $order->name }},

Cảm ơn bạn đã đặt hàng tại **{{ config('app.name') }}**.
Hệ thống ghi nhận đơn hàng **#{{ $order->id }}** của bạn đang ở trạng thái **Chưa thanh toán**. 

@if($reminderStep === 1)
Để đảm bảo đơn hàng được xử lý và giao đến bạn sớm nhất, vui lòng hoàn tất thanh toán trong vòng 45 phút tới.
@else
**Lưu ý:** Nếu bạn không hoàn tất thanh toán trong vòng 30 phút tới, đơn hàng của bạn sẽ tự động bị hủy để giải phóng kho hàng cho các khách hàng khác.
@endif

## Chi tiết số tiền cần thanh toán
**Tổng cộng:** <span style="color:#d32f2f;font-size:18px;font-weight:bold;">{{ number_format($order->final_total) }} đ</span>
**Phương thức:** {{ $order->payment_method == 'COD' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản / Phương thức định sẵn' }}

Nếu bạn gặp khó khăn trong quá trình thanh toán, vui lòng liên hệ với chúng tôi qua email này hoặc hotline để được hỗ trợ.

Trân trọng,<br>
Đội ngũ {{ config('app.name') }}
</x-mail::message>
