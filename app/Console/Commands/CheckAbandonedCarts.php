<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CartAbandonment;
use App\Jobs\SendAbandonedCartEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckAbandonedCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:check-abandoned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra giỏ hàng bị bỏ quên qua 24h và gửi email nhắc nhở';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to check for abandoned carts...');
        Log::info('Running CheckAbandonedCarts command.');

        // Tìm các giỏ hàng đã bị bỏ quên quá 2 giờ nhưng chưa gửi mail
        $timeThreshold = Carbon::now()->subHours(2);

        $abandonedCarts = CartAbandonment::whereNotNull('user_id')
                            ->where('status', 'abandoned')
                            ->where('abandoned_at', '<=', $timeThreshold)
                            ->get();

        if ($abandonedCarts->isEmpty()) {
            $this->info('No abandoned carts found needing notification.');
            return 0;
        }

        $count = 0;
        foreach ($abandonedCarts as $cart) {
            // Đẩy vào queue để xử lý gửi mail ngầm
            SendAbandonedCartEmail::dispatch($cart);
            
            // Tạm thời đánh dấu pending để khỏi quét lại liên tục
            $cart->status = 'pending_notification';
            $cart->save();
            
            $count++;
        }

        $this->info("Dispatched {$count} abandoned cart notification jobs.");
        return 0;
    }
}
