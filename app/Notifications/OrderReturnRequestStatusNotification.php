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

            'refunded' => $message
                ->greeting('Xin chào '.$notifiable->name.'!')
                ->line('Yêu cầu trả hàng hoàn tiền cho đơn **#'.$this->returnRequest->order_id.'** đã **hoàn tất**.')
                ->line('Số tiền '.number_format($this->returnRequest->refund_amount).'đ đã được cộng vào ví của bạn.')
                ->action('Kiểm tra ví', route('account.index', ['tab' => 'wallet'])),

            'exchanged' => $message
                ->greeting('Xin chào '.$notifiable->name.'!')
                ->line('Yêu cầu đổi hàng cho đơn **#'.$this->returnRequest->order_id.'** đã được **xử lý thành công**.')
                ->line('Chúng tôi sẽ sớm gửi sản phẩm mới đến cho bạn.')
                ->action('Xem chi tiết', route('account.orders.show', $this->returnRequest->order_id)),

            default => $message
                ->greeting('Xin chào '.$notifiable->name.'!')
                ->line('Yêu cầu hoàn trả đơn #'.$this->returnRequest->order_id.' đã '.$statusText.'.')
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
            'shipping_back' => 'đang được vận chuyển về kho',
            'received' => 'đã được nhận tại kho (Đang chờ xử lý cuối cùng)',
            'refunded' => 'hoàn tất (Đã hoàn tiền vào ví)',
            'exchanged' => 'hoàn tất (Đã đổi hàng thành công)',
            'rejected' => 'bị từ chối',
            default => 'thay đổi trạng thái',
        };
    }
}
