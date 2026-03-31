<?php

namespace App\Mail;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $coupon;

    public function __construct(User $user, ?Coupon $coupon = null)
    {
        $this->user = $user;
        $this->coupon = $coupon;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Chào mừng bạn đến với '.config('app.name').'! 🎉',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.welcome',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
