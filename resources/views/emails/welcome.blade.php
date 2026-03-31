<x-mail::message>
# Chào mừng bạn, {{ $user->name }}! 🎉

Cảm ơn bạn đã đăng ký tài khoản tại **{{ config('app.name') }}** — nơi mua sắm thời trang uy tín hàng đầu.

Tài khoản của bạn đã được tạo thành công. Dưới đây là thông tin:

- **Họ tên:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Số điện thoại:** {{ $user->phone ?? 'Chưa cập nhật' }}

@if($coupon)
---

## 🎁 Quà tặng chào mừng

Để chào đón bạn, chúng tôi tặng bạn mã giảm giá đặc biệt:

<x-mail::panel>
**Mã giảm giá:** {{ $coupon->code }}

**Giá trị:** {{ $coupon->getFormattedValue() }}

@if($coupon->min_order_amount)
**Điều kiện:** Đơn hàng từ {{ number_format($coupon->min_order_amount, 0, ',', '.') }}đ
@endif

**Số lần sử dụng:** {{ $coupon->usage_limit }} lần
</x-mail::panel>
@endif

<x-mail::button :url="url('/shop')">
Bắt đầu mua sắm ngay
</x-mail::button>

Nếu bạn cần hỗ trợ, đừng ngần ngại liên hệ với chúng tôi qua email hoặc gọi **HOTLINE: 0123.456.789**.

Trân trọng,<br>
Đội ngũ {{ config('app.name') }}
</x-mail::message>
