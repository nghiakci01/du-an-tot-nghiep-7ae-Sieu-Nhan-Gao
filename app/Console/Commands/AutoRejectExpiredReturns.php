<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoRejectExpiredReturns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'returns:auto-reject-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động từ chối các yêu cầu trả hàng pending quá 14 ngày';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredDays = 14;
        $expiredDate = now()->subDays($expiredDays);

        $expiredReturns = \App\Models\OrderReturnRequest::where('status', 'pending')
            ->where('created_at', '<', $expiredDate)
            ->get();

        $count = 0;
        foreach ($expiredReturns as $returnRequest) {
            // Từ chối với lý do quá hạn
            $returnService = app(\App\Services\ReturnService::class);
            $systemUser = \App\Models\User::where('role', 'admin')->first(); // Hoặc tạo user system

            if ($systemUser) {
                $returnService->reject($returnRequest, $systemUser, 'Tự động từ chối: Quá hạn xử lý (14 ngày)');
                $count++;
            }
        }

        $this->info("Đã từ chối {$count} yêu cầu trả hàng quá hạn.");
    }
}
