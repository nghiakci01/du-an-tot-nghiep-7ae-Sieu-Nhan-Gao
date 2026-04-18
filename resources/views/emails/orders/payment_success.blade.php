<x-mail::message>
# ✅ Thanh toán thành công!

Xin chào **{{ $order->name }}**,

Chúng tôi đã nhận được thanh toán cho đơn hàng **#{{ $order->id }}** của bạn.

## Thông tin thanh toán
- **Mã đơn hàng:** #{{ $order->id }}
- **Phương thức:** {{ $order->payment_method == 'COD' ? 'Thanh toán khi nhận hàng' : ($order->payment_method == 'VNPAY' ? 'VNPay' : 'Chuyển khoản ngân hàng') }}
- **Trạng thái:** <span style="color: #27ae60; font-weight: bold;">ĐÃ THANH TOÁN</span>
- **Thời gian:** {{ $order->updated_at->format('d/m/Y H:i') }}

## Hóa đơn chi tiết

<x-mail::table>
| Sản phẩm | SL | Đơn giá | Thành tiền |
|:---|:---:|---:|---:|
@foreach($order->items as $item)
| **{{ $item->product->name ?? 'Sản phẩm' }}** | {{ $item->quantity }} | {{ number_format($item->price) }}đ | {{ number_format($item->price * $item->quantity) }}đ |
@endforeach
</x-mail::table>

---

<x-mail::table>
| | |
|:---|---:|
| **Tổng tiền hàng:** | {{ number_format($order->subtotal) }}đ |
| **Phí vận chuyển:** | {{ $order->shipping_fee > 0 ? number_format($order->shipping_fee) . 'đ' : 'Miễn phí' }} |
| **Giảm giá:** | -{{ number_format($order->discount_amount) }}đ |
| **Tổng thanh toán:** | **<span style="color:#27ae60;font-size:18px;">{{ number_format($order->final_total) }}đ</span>** |
</x-mail::table>

<x-mail::button :url="route('order-tracking.index', ['order_id' => $order->id, 'contact' => $order->email])">
Tra cứu đơn hàng
</x-mail::button>

Đơn hàng sẽ được xử lý và giao đến bạn trong thời gian sớm nhất. Cảm ơn bạn đã tin tưởng mua sắm tại **{{ config('app.name') }}**!

Trân trọng,<br>
Đội ngũ {{ config('app.name') }}
</x-mail::message>
