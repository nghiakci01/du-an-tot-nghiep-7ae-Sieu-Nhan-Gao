<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderReturnRequestNotification extends Notification
{
    use Queueable;

    protected $returnRequest;

    public function __construct($returnRequest)
    {
        $this->returnRequest = $returnRequest;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_order_return_request',
            'return_request_id' => $this->returnRequest->id,
            'order_id' => $this->returnRequest->order_id,
            'customer_name' => $this->returnRequest->user->name,
            'reason' => $this->returnRequest->reason,
            'message' => 'Có yêu cầu hoàn trả mới cho đơn hàng #' . $this->returnRequest->order_id . ' từ ' . $this->returnRequest->user->name,
            'link' => route('admin.returns.index', ['status' => 'pending']),
        ];
    }
}
