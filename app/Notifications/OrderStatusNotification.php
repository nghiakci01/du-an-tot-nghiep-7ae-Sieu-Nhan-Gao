<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Mail\OrderShippedMail;

class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new notification instance.
     *
     * @param Order $order
     * @param string|null $oldStatus
     * @param string $newStatus
     */
    public function __construct(Order $order, ?string $oldStatus, string $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = ['database'];

        // If it's shipped, and we have an email, also send an email
        if ($this->newStatus === Order::STATUS_SHIPPED && $notifiable->email) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Mail\Mailable
     */
    public function toMail($notifiable)
    {
        // We reuse the existing OrderShippedMail
        return (new OrderShippedMail($this->order))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // Generate a descriptive physical text
        $message = "Đơn hàng #{$this->order->id} của bạn đã cập nhật trạng thái thành: {$this->order->status_text}.";

        return [
            'order_id' => $this->order->id,
            'status' => $this->order->status,
            'message' => $message,
            'type' => 'order_status_update',
            'url' => route('account.orders.show', $this->order->id)
        ];
    }
}
