<x-mail::message>
# Xác nhận đơn hàng #{{ $order->id }}

Xin chào {{ $order->name }},

Cảm ơn bạn đã đặt hàng tại **{{ config('app.name') }}**.
Đơn hàng của bạn đã được tiếp nhận và đang trong quá trình xử lý.

## Thông tin đơn hàng
- **Mã đơn hàng:** #{{ $order->id }}
- **Ngày đặt:** {{ $order->created_at->format('d/m/Y H:i') }}
- **Phương thức thanh toán:** {{ $order->payment_method == 'COD' ? 'Thanh toán khi nhận hàng' : ($order->payment_method == 'VNPAY' ? 'Thanh toán qua VNPAY' : 'Chuyển khoản ngân hàng') }}
- **Trạng thái thanh toán:** {{ $order->status == 'confirmed' ? 'Đã thanh toán' : 'Chưa thanh toán' }}

## Địa chỉ giao hàng
**{{ $order->name }}**<br>
Số điện thoại: **{{ $order->phone }}**<br>
Email: {{ $order->email }}<br>
Địa chỉ: {{ $order->address }}, {{ $order->province }}<br>
@if($order->note)
Ghi chú: {{ $order->note }}
@endif

## Chi tiết sản phẩm
<x-mail::table>
| Sản phẩm | Hình ảnh | Số lượng | Đơn giá | Thành tiền |
|:---|:---:|:---:|---:|---:|
@foreach($order->items as $item)
<?php 
$imagePath = $item->variant && $item->variant->product && $item->variant->product->image ? asset('storage/' . $item->variant->product->image) : null;
$imgTag = $imagePath ? "<img src='{$imagePath}' width='50' style='border-radius:5px'>" : "No Image";
?>
| **{{ $item->product->name ?? 'Sản phẩm' }}**<br><small>Biến thể: {{ $item->variant->sku ?? ($item->variant->attribute_values_summary ?? 'N/A') }}</small> | {!! $imgTag !!} | {{ $item->quantity }} | {{ number_format($item->price) }} đ | {{ number_format($item->price * $item->quantity) }} đ |
@endforeach
</x-mail::table>

---

<x-mail::table>
| | |
|:---|---:|
| **Tổng tiền hàng:** | {{ number_format($order->subtotal) }} đ |
| **Phí vận chuyển:** | {{ $order->shipping_fee > 0 ? number_format($order->shipping_fee) . ' đ' : 'Miễn phí' }} |
| **Khuyến mãi/Giảm giá:** | -{{ number_format($order->discount_amount) }} đ |
| **Tổng cộng:** | **<span style="color:#d32f2f;font-size:18px;">{{ number_format($order->final_total) }} đ</span>** |
</x-mail::table>

<x-mail::button :url="route('order-tracking.index', ['order_id' => $order->id, 'contact' => $order->email])">
Tra Cứu Đơn Hàng
</x-mail::button>

Nếu bạn có bất kỳ câu hỏi nào về trạng thái đơn hàng, vui lòng liên hệ với chúng tôi qua email này hoặc gọi **HOTLINE: 0123.456.789**.

Trân trọng,<br>
Đội ngũ {{ config('app.name') }}
</x-mail::message>