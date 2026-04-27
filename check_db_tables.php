<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$tables = ['coupon_user', 'users', 'coupons'];
foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "Table '$table' exists.\n";
    } else {
        echo "Table '$table' DOES NOT exist.\n";
    }
}
