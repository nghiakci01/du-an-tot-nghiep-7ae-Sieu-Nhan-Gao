<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Xác thực email — '.config('app.name'))
            ->greeting('Xin chào!')
            ->line('Cảm ơn bạn đã đăng ký tài khoản tại **'.config('app.name').'**.')
            ->line('Vui lòng nhấn nút bên dưới để xác thực địa chỉ email của bạn:')
            ->action('Xác thực email ngay', $url)
            ->line('Link xác thực có hiệu lực trong 60 phút.')
            ->line('Nếu bạn không tạo tài khoản, bạn có thể bỏ qua email này.')
            ->salutation('Trân trọng, Đội ngũ '.config('app.name'));
    }
}
