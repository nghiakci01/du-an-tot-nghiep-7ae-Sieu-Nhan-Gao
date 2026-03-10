<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

// Tự động kiểm tra và hủy đơn hàng online chưa thanh toán (chạy mỗi giờ 1 lần)
Schedule::command('orders:cancel-unpaid')->hourly();
