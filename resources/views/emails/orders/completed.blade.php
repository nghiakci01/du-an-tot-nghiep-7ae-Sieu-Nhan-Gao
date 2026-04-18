<x-mail::message>
# 🎉 Đơn hàng đã giao thành công!

Xin chào **{{ $order->name }}**,

Đơn hàng **#{{ $order->id }}** đã được giao thành công đến bạn. Cảm ơn bạn đã tin tưởng mua sắm tại **{{ config('app.name') }}**!

## Sản phẩm đã nhận

<x-mail::table>
| Sản phẩm | SL | Thành tiền |
|:---|:---:|---:|
@foreach($order->items as $item)
| **{{ $item->product->name ?? 'Sản phẩm' }}** | {{ $item->quantity }} | {{ number_format($item->price * $item->quantity) }}đ |
@endforeach
</x-mail::table>

**Tổng thanh toán:** {{ number_format($order->final_total) }}đ

---

## ⭐ Đánh giá sản phẩm

Trải nghiệm mua sắm của bạn thế nào? Hãy chia sẻ đánh giá để giúp những người mua hàng khác nhé!

@foreach($order->items as $item)
@if($item->product && $item->product->slug)
<x-mail::button :url="url('/product/' . $item->product->slug . '#reviews')" color="success">
⭐ Đánh giá "{{ Str::limit($item->product->name, 30) }}"
</x-mail::button>
@endif
@break
@endforeach

---

Nếu có bất kỳ vấn đề nào với sản phẩm, bạn có thể yêu cầu hoàn hàng trong vòng 7 ngày.

<x-mail::button :url="url('/my-account')" color="primary">
Xem đơn hàng của tôi
</x-mail::button>

Trân trọng,<br>
Đội ngũ {{ config('app.name') }}
</x-mail::message>
