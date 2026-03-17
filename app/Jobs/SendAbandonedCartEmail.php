<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Models\CartAbandonment;
use Illuminate\Support\Facades\Mail;
use App\Mail\AbandonedCartMail;
use Illuminate\Support\Facades\Log;

class SendAbandonedCartEmail implements ShouldQueue
{
    use Queueable;

    protected $cartAbandonment;

    /**
     * Create a new job instance.
     */
    public function __construct(CartAbandonment $cartAbandonment)
    {
        $this->cartAbandonment = $cartAbandonment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if ($this->cartAbandonment->user && $this->cartAbandonment->user->email) {
                Mail::to($this->cartAbandonment->user->email)->send(new AbandonedCartMail($this->cartAbandonment));
                
                // Cập nhật trạng thái
                $this->cartAbandonment->status = 'notified';
                $this->cartAbandonment->save();
                
                Log::info('Đã gửi email nhắc nhở giỏ hàng cho user ' . $this->cartAbandonment->user->email);
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi gửi email giỏ hàng bị bỏ quên: ' . $e->getMessage());
        }
    }
}
