<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
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
            'type' => 'new_order',
            'order_id' => $this->order->id,
            'customer_name' => $this->order->name,
            'total_price' => $this->order->final_total,
            'message' => 'Bạn có một đơn hàng mới từ ' . $this->order->name,
            'link' => route('admin.orders.show', $this->order->id),
        ];
    }
}
