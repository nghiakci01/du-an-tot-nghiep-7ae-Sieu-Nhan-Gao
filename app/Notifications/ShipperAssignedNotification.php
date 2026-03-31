<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShipperAssignedNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Bạn có đơn hàng mới cần giao! #' . $this->order->id)
                    ->greeting('Xin chào ' . $notifiable->name . '!')
                    ->line('Admin vừa gán cho bạn một đơn hàng mới cần giao.')
                    ->line('Mã đơn hàng: #' . $this->order->id)
                    ->line('Địa chỉ giao hàng: ' . $this->order->shipping_address)
                    ->line('Tổng tiền: ' . number_format($this->order->final_total) . 'đ')
                    ->action('Xem chi tiết đơn hàng', route('staff.orders.show', $this->order->id))
                    ->line('Vui lòng thực hiện giao nhận đơn hàng sớm nhất có thể.')
                    ->line('Cảm ơn bạn!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'message' => 'Bạn được gán đơn hàng mới: #' . $this->order->id,
            'url' => route('staff.orders.show', $this->order->id),
            'type' => 'order_assigned'
        ];
    }
}
