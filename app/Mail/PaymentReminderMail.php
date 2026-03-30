<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $paymentUrl;
    public $reminderStep;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $paymentUrl, int $reminderStep)
    {
        $this->order = $order;
        $this->paymentUrl = $paymentUrl;
        $this->reminderStep = $reminderStep;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->reminderStep === 1 
            ? 'Nhắc nhở thanh toán đơn hàng #' . $this->order->id
            : 'Thông báo hủy đơn hàng sắp tới: Đơn hàng #' . $this->order->id . ' chưa thanh toán';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.payment_reminder',
            with: [
                'order' => $this->order,
                'paymentUrl' => $this->paymentUrl,
                'reminderStep' => $this->reminderStep,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
