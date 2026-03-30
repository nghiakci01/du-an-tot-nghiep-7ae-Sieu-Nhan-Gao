<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

// Tự động kiểm tra nhắc nhở và hủy đơn hàng online chưa thanh toán (chạy mỗi phút)
Schedule::command('app:check-payment-reminders')->everyMinute();

// Quét giỏ hàng bị bỏ quên quá 2 tiếng (chạy mỗi 15 phút)
Schedule::command('cart:check-abandoned')->everyFifteenMinutes();
