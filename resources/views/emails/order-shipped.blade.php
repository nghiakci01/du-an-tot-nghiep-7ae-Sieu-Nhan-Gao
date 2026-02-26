<x-mail::message>
    # Đơn hàng của bạn đang được giao!

    Xin chào {{ $order->user_name ?? ($order->user->name ?? 'Bạn') }},

    Tin vui cho bạn! Đơn hàng **#{{ $order->id }}** của bạn đã được xuất kho và đang trong quá trình vận chuyển.

    <x-mail::panel>
        **Mã đơn hàng:** #{{ $order->id }}<br>
        **Ngày đặt:** {{ $order->created_at->format('d/m/Y H:i') }}<br>
        **Tổng tiền:** {{ number_format($order->total_price) }} đ<br>
    </x-mail::panel>

    Đơn hàng sẽ sớm được giao đến địa chỉ của bạn. Vui lòng chú ý điện thoại để nhận hàng nhé.

    <x-mail::button :url="url('/account/orders/' . $order->id)">
        Xem Chi Tiết Đơn Hàng
    </x-mail::button>

    Cảm ơn bạn đã mua sắm tại {{ config('app.name') }}!

    Trân trọng,<br>
    Đội ngũ {{ config('app.name') }}
</x-mail::message>