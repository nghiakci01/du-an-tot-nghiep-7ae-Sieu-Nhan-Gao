<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CancelUnpaidOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-unpaid {--minutes=60 : Số phút giới hạn cho đơn hàng chưa thanh toán}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động hủy các đơn hàng thanh toán online (VNPAY, Momo...) nhưng chưa được thanh toán sau một khoảng thời gian nhất định (Mặc định: 60 phút)';

    /**
     * Execute the console command.
     */
    public function handle(OrderService $orderService)
    {
        $minutes = $this->option('minutes');

        // Nếu chạy không truyền params (mặc định 60), ta ưu tiên đọc từ Cài đặt Admin
        if ($minutes == 60) {
            $settingMinutes = \App\Models\Setting::where('key', 'auto_cancel_unpaid_order_minutes')->value('value');
            if (is_numeric($settingMinutes) && $settingMinutes > 0) {
                $minutes = $settingMinutes;
            }
        }
        $timeLimit = Carbon::now()->subMinutes($minutes);

        $this->info("Đang tìm kiếm các đơn hàng chưa thanh toán trước {$timeLimit}...");

        // Tìm các đơn hàng:
        // - Trạng thái: pending (Chờ xác nhận)
        // - Phương thức thanh toán KHÁC COD (vì COD thanh toán khi nhận hàng)
        // - Trạng thái thanh toán: pending (Chưa thanh toán)
        // - Thời gian tạo: cũ hơn $hours (mặc định 24h)
        $orders = Order::where('status', Order::STATUS_PENDING)
            ->where('payment_method', '!=', 'cod')
            ->where('payment_status', 'pending')
            ->where('created_at', '<', $timeLimit)
            ->get();

        if ($orders->isEmpty()) {
            $this->info("Không có đơn hàng nào cần hủy.");
            return;
        }

        $count = 0;

        foreach ($orders as $order) {
            try {
                // Sử dụng OrderService để đảm bảo flow hủy đơn hàng, phục hồi kho được diễn ra thống nhất
                $orderService->updateOrderStatus(
                    $order, 
                    Order::STATUS_CANCELLED, 
                    null, // User là null do hệ thống tự động chạy
                    "Hệ thống tự động hủy đơn do quá {$minutes} phút không thanh toán."
                );

                $count++;
                $this->line("- Đã hủy đơn hàng #{$order->id}");
            } catch (\Exception $e) {
                $this->error("Lỗi khi hủy đơn hàng #{$order->id}: " . $e->getMessage());
                Log::error("CancelUnpaidOrders Job error on Order #{$order->id}: " . $e->getMessage());
            }
        }

        $this->info("Hoàn tất! Đã tự động hủy {$count} đơn hàng.");
    }
}
