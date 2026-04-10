<?php

namespace App\Notifications;

use App\Models\Order;
use App\Mail\OrderCompletedMail;
use App\Mail\OrderShippedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $oldStatus;
    protected $newStatus;

    public function __construct(Order $order, ?string $oldStatus, string $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        $channels = ['database'];

        // Send email for key status changes (if user has email)
        if ($notifiable->email) {
            $emailStatuses = [
                Order::STATUS_CONFIRMED,
                Order::STATUS_SHIPPED,
                Order::STATUS_COMPLETED,
            ];
            if (in_array($this->newStatus, $emailStatuses)) {
                $channels[] = 'mail';
            }
        }

        return $channels;
    }

    public function toMail($notifiable)
    {
        // Use dedicated Mailable for shipped and completed
        if ($this->newStatus === Order::STATUS_SHIPPED) {
            return (new OrderShippedMail($this->order))->to($notifiable->email);
        }

        if ($this->newStatus === Order::STATUS_COMPLETED) {
            $mail = (new OrderCompletedMail($this->order))->to($notifiable->email);
            // Optionally add custom content or use a different mailable if needed
            // For now, we assume OrderCompletedMail handles it.
            return $mail;
        }

        // For processing status, use MailMessage
        $mailMessage = (new MailMessage)
            ->subject($this->getSubject())
            ->greeting('Xin chào '.$notifiable->name.'!');

        if ($this->newStatus === Order::STATUS_COMPLETED) {
            $mailMessage->line("Đơn hàng #{$this->order->id} của bạn đã giao hàng thành công.")
                ->line("Chúng tôi hy vọng bạn hài lòng với các sản phẩm vừa nhận được. Bạn hãy dành chút thời gian đánh giá sản phẩm để nhận ưu đãi cho các đơn hàng sau nhé!")
                ->action('Đánh giá sản phẩm', route('account.orders.show', $this->order->id) . '#tab-reviews')
                ->line('Cảm ơn bạn đã mua sắm tại '.config('app.name').'!');
        } else {
            $mailMessage->line($this->getStatusMessage())
                ->action('Xem đơn hàng', route('account.orders.show', $this->order->id))
                ->line('Cảm ơn bạn đã mua sắm tại '.config('app.name').'!');
        }

        return $mailMessage->salutation('Trân trọng, Đội ngũ '.config('app.name'));
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->order->status,
            'message' => $this->getStatusMessage(),
            'type' => 'order_status_update',
            'url' => route('account.orders.show', $this->order->id) . ($this->newStatus === Order::STATUS_COMPLETED ? '#tab-reviews' : ''),
        ];
    }

    protected function getSubject(): string
    {
        return match ($this->newStatus) {
            Order::STATUS_CONFIRMED => 'Đơn hàng #'.$this->order->id.' đã được xác nhận',
            Order::STATUS_SHIPPED => 'Đơn hàng #'.$this->order->id.' đang được giao',
            Order::STATUS_COMPLETED => 'Đơn hàng #'.$this->order->id.' đã giao thành công!',
            default => 'Cập nhật đơn hàng #'.$this->order->id,
        };
    }

    protected function getStatusMessage(): string
    {
        return match ($this->newStatus) {
            Order::STATUS_CONFIRMED => "Đơn hàng #{$this->order->id} đã được xác nhận và đang chuẩn bị giao cho đơn vị vận chuyển.",
            Order::STATUS_SHIPPED => "Đơn hàng #{$this->order->id} đã được giao cho đơn vị vận chuyển.",
            Order::STATUS_COMPLETED => "Đơn hàng #{$this->order->id} đã giao thành công. Bạn hãy dành chút thời gian đánh giá sản phẩm để giúp cửa hàng cải thiện nhé!",
            default => "Đơn hàng #{$this->order->id} đã cập nhật trạng thái thành: {$this->order->status_text}.",
        };
    }
}
