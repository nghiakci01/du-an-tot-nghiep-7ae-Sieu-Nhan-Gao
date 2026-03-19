<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderReturnRequestStatusNotification extends Notification
{
    use Queueable;

    protected $returnRequest;
    protected $status;

    public function __construct($returnRequest, $status)
    {
        $this->returnRequest = $returnRequest;
        $this->status = $status;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusText = match($this->status) {
            'approved' => 'được chấp nhận (Chờ bạn gửi hàng)',
            'shipping' => 'đang được vận chuyển về kho',
            'received' => 'đã được nhận tại kho (Đang chờ hoàn tiền)',
            'completed' => 'hoàn thành (Đã hoàn tiền vào ví)',
            'rejected' => 'bị từ chối',
            default => 'thay đổi trạng thái',
        };

        return [
            'type' => 'order_return_status',
            'return_request_id' => $this->returnRequest->id,
            'order_id' => $this->returnRequest->order_id,
            'status' => $this->status,
            'message' => 'Yêu cầu hoàn trả đơn hàng #' . $this->returnRequest->order_id . ' đã ' . $statusText,
            'admin_note' => $this->returnRequest->admin_note,
            'link' => route('account.orders.show', $this->returnRequest->order_id),
        ];
    }
}
