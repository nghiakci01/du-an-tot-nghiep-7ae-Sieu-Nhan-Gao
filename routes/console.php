<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

// Tự động kiểm tra nhắc nhở và hủy đơn hàng online chưa thanh toán (chạy mỗi phút)
Schedule::command('app:check-payment-reminders')->everyMinute();

// Tự động từ chối return requests quá hạn (chạy hàng ngày lúc 9h sáng)
Schedule::command('returns:auto-reject-expired')->dailyAt('09:00');
