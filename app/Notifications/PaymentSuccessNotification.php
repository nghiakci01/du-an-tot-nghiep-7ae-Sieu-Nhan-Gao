<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentSuccessNotification extends Notification
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
            'type' => 'payment_success',
            'order_id' => $this->order->id,
            'final_total' => $this->order->final_total,
            'message' => 'Đơn hàng #' . $this->order->id . ' đã được thanh toán thành công.',
            'link' => route('admin.orders.show', $this->order->id),
        ];
    }
}
