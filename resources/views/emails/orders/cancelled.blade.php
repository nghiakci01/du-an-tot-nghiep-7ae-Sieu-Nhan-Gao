<x-mail::message>
# Xác nhận hủy đơn hàng

Xin chào **{{ $order->name }}**,

Đơn hàng **#{{ $order->id }}** của bạn đã được hủy thành công.

## Thông tin đơn hàng đã hủy
- **Mã đơn hàng:** #{{ $order->id }}
- **Ngày đặt:** {{ $order->created_at->format('d/m/Y H:i') }}
- **Tổng giá trị:** {{ number_format($order->final_total) }}đ

@if($reason)
**Lý do hủy:** {{ $reason }}
@endif

@if($order->payment_status === 'paid')
---

## 💰 Thông tin hoàn tiền

Đơn hàng đã được thanh toán. Chúng tôi sẽ tiến hành hoàn tiền **{{ number_format($order->final_total) }}đ** về phương thức thanh toán ban đầu trong vòng **3-5 ngày làm việc**.

Nếu bạn chưa nhận được hoàn tiền sau 5 ngày, vui lòng liên hệ HOTLINE: **0123.456.789**.
@endif

---

Bạn vẫn có thể đặt lại đơn hàng bất kỳ lúc nào. Chúng tôi luôn sẵn sàng phục vụ!

<x-mail::button :url="url('/shop')">
Tiếp tục mua sắm
</x-mail::button>

Trân trọng,<br>
Đội ngũ {{ config('app.name') }}
</x-mail::message>
