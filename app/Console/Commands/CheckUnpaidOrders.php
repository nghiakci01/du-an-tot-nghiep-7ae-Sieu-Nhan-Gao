<?php

namespace App\Console\Commands;

use App\Mail\OrderAutoCancelledMail;
use App\Mail\PaymentReminderMail;
use App\Models\Order;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckUnpaidOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-payment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra và nhắc nhở / tự động hủy đơn hàng thanh toán online chưa thanh toán';

    public function __construct(
        protected OrderService $orderService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Query all unpaid online orders that are not yet cancelled/failed/completed
        $orders = Order::where('payment_status', '!=', 'paid')
            ->where('payment_method', '!=', 'COD')
            ->whereNotIn('status', [Order::STATUS_CANCELLED, Order::STATUS_FAILED, Order::STATUS_RETURNED, Order::STATUS_PARTIALLY_RETURNED, Order::STATUS_COMPLETED])
            ->get();

        $count = $orders->count();
        $this->info("Đang kiểm tra $count đơn hàng thanh toán online chưa hoàn tất...");

        foreach ($orders as $order) {
            $minutesDiff = $order->created_at->diffInMinutes($now);

            // Xử lý hủy đơn sau 60 phút
            if ($minutesDiff >= 60) {
                try {
                    $order->update(['payment_status' => 'failed']);
                    $this->orderService->updateOrderStatus(
                        $order, 
                        Order::STATUS_CANCELLED, 
                        null, 
                        'Hệ thống tự động hủy do quá hạn thanh toán (60 phút).'
                    );
                    
                    if ($order->email) {
                        Mail::to($order->email)->send(new OrderAutoCancelledMail($order));
                    }
                    
                    $this->info("Đã hủy đơn hàng #{$order->id} (quá $minutesDiff phút).");
                } catch (\Exception $e) {
                    $this->error("Lỗi khi auto hủy đơn {$order->id}: " . $e->getMessage());
                    Log::error("Lỗi khi auto hủy đơn {$order->id}: " . $e->getMessage());
                }

            // Xử lý nhắc nhở lần 2 sau 30 phút
            } elseif ($minutesDiff >= 30 && $order->reminder_step == 1) {
                $this->sendReminder($order, 2);

            // Xử lý nhắc nhở lần 1 sau 15 phút
            } elseif ($minutesDiff >= 15 && $order->reminder_step == 0) {
                $this->sendReminder($order, 1);
            }
        }

        $this->info("Đã kiểm tra xong.");
    }

    /**
     * Gửi email nhắc nhở
     */
    private function sendReminder(Order $order, int $step)
    {
        try {
            $paymentUrl = '';
            
            // No online payment URL needed after VNPAY removal

            if ($order->email) {
                Mail::to($order->email)->send(new PaymentReminderMail($order, $paymentUrl, $step));
            }
            
            $order->update(['reminder_step' => $step]);
            
            $this->info("Đã gửi nhắc nhở lần $step cho đơn hàng #{$order->id}.");
        } catch (\Exception $e) {
            $this->error("Lỗi gửi nhắc nhở lần $step cho đơn {$order->id}: " . $e->getMessage());
            Log::error("Lỗi gửi nhắc nhở lần $step cho đơn {$order->id}: " . $e->getMessage());
        }
    }
}
