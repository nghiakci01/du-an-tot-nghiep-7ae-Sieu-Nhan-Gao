<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReturnStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $returnRequest;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct($returnRequest, $status)
    {
        $this->returnRequest = $returnRequest;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->status) {
            'approved' => 'Yêu cầu trả hàng của bạn đã được duyệt',
            'shipping_back' => 'Hàng trả của bạn đang được vận chuyển về',
            'received' => 'Hàng trả của bạn đã được nhận tại kho',
            'refunded' => 'Hoàn tiền cho đơn hàng của bạn đã được xử lý',
            'exchanged' => 'Đơn hàng đổi của bạn đã được xử lý',
            'rejected' => 'Yêu cầu trả hàng của bạn đã bị từ chối',
            default => 'Cập nhật trạng thái yêu cầu trả hàng',
        };

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
            view: 'emails.return_status',
            with: [
                'returnRequest' => $this->returnRequest,
                'status' => $this->status,
            ],
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
