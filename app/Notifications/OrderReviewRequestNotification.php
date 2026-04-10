<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderReviewRequestNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bạn thấy sản phẩm từ đơn hàng #' . $this->order->id . ' thế nào?')
            ->greeting('Xin chào ' . $notifiable->name . '!')
            ->line('Đơn hàng #' . $this->order->id . ' của bạn đã hoàn thành.')
            ->line('Bạn hãy dành chút thời gian đánh giá các sản phẩm bạn đã nhận nhé. Ý kiến của bạn rất quan trọng với chúng tôi!')
            ->action('Đánh giá ngay', route('account.orders.show', $this->order->id) . '#tab-reviews')
            ->line('Cảm ơn bạn đã đồng hành cùng ' . config('app.name') . '!');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'message' => 'Bạn ơi, hãy để lại đánh giá cho đơn hàng #' . $this->order->id . ' nhé!',
            'type' => 'review_request',
            'url' => route('account.orders.show', $this->order->id) . '#tab-reviews',
        ];
    }
}
