<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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
        $channels = ['database'];

        // Send email for key return status changes
        if ($notifiable->email) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusText = $this->getStatusText();
        $message = (new MailMessage)
            ->subject('Cập nhật hoàn hàng — Đơn #'.$this->returnRequest->order_id.' — '.config('app.name'));

        match ($this->status) {
            'approved' => $message
                ->greeting('Xin chào '.$notifiable->name.'!')
                ->line('Yêu cầu hoàn hàng cho đơn **#'.$this->returnRequest->order_id.'** đã được **chấp nhận**.')
                ->line('Vui lòng gửi sản phẩm về kho theo hướng dẫn trong tài khoản của bạn.')
                ->action('Xem chi tiết', route('account.orders.show', $this->returnRequest->order_id)),

            'rejected' => $message
                ->greeting('Xin chào '.$notifiable->name.'!')
                ->line('Yêu cầu hoàn hàng cho đơn **#'.$this->returnRequest->order_id.'** đã bị **từ chối**.')
                ->line($this->returnRequest->admin_note ? 'Lý do: '.$this->returnRequest->admin_note : '')
                ->action('Xem chi tiết', route('account.orders.show', $this->returnRequest->order_id)),

            'completed' => $message
                ->greeting('Xin chào '.$notifiable->name.'!')
                ->line('Hoàn hàng cho đơn **#'.$this->returnRequest->order_id.'** đã **hoàn tất**.')
                ->line('Tiền hoàn đã được cộng vào ví/tài khoản của bạn.')
                ->action('Kiểm tra tài khoản', url('/my-account')),

            default => $message
                ->greeting('Xin chào '.$notifiable->name.'!')
                ->line('Yêu cầu hoàn hàng cho đơn #'.$this->returnRequest->order_id.' đã '.$statusText.'.')
                ->action('Xem chi tiết', route('account.orders.show', $this->returnRequest->order_id)),
        };

        return $message->salutation('Trân trọng, Đội ngũ '.config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        $statusText = $this->getStatusText();

        return [
            'type' => 'order_return_status',
            'return_request_id' => $this->returnRequest->id,
            'order_id' => $this->returnRequest->order_id,
            'status' => $this->status,
            'message' => 'Yêu cầu hoàn trả đơn hàng #'.$this->returnRequest->order_id.' đã '.$statusText,
            'admin_note' => $this->returnRequest->admin_note,
            'link' => route('account.orders.show', $this->returnRequest->order_id),
        ];
    }

    protected function getStatusText(): string
    {
        return match ($this->status) {
            'approved' => 'được chấp nhận (Chờ bạn gửi hàng)',
            'shipping' => 'đang được vận chuyển về kho',
            'received' => 'đã được nhận tại kho (Đang chờ hoàn tiền)',
            'completed' => 'hoàn thành (Đã hoàn tiền vào ví)',
            'rejected' => 'bị từ chối',
            default => 'thay đổi trạng thái',
        };
    }
}
