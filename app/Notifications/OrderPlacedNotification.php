<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'message' => "Chúc mừng! Đơn hàng #{$this->order->id} của bạn đã được đặt thành công. Chúng tôi sẽ sớm xử lý đơn hàng của bạn.",
            'type' => 'order_placed',
            'url' => route('account.orders.show', $this->order->id)
        ];
    }
}
