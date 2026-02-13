# Thông báo tin nhắn liên hệ mới

Xin chào,

Hệ thống vừa nhận được một tin nhắn liên hệ mới từ khách hàng qua trang website.

## Chi tiết tin nhắn:
**Người gửi:** {{ $contactMessage->name }}  
**Email:** {{ $contactMessage->email }}  
**Tiêu đề:** {{ $contactMessage->subject }}  

### Nội dung:
{{ $contactMessage->message }}

<x-mail::button :url="route('admin.contact-messages.show', $contactMessage->id)">
Xem chi tiết trong trang quản trị
</x-mail::button>

Trân trọng,<br>
{{ config('app.name') }}
