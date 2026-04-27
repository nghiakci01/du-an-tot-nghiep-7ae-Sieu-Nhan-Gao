<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$tables = DB::select('SHOW TABLES');
foreach($tables as $table) {
    foreach((array)$table as $name) {
        echo $name . PHP_EOL;
    }
}

echo "\nChecking coupon_user existence...\n";
if (Schema::hasTable('coupon_user')) {
    echo "TABLE coupon_user: EXISTS\n";
} else {
    echo "TABLE coupon_user: MISSING\n";
}

echo "\nChecking DomPDF existence...\n";
if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
    echo "CLASS DomPDF: EXISTS\n";
} else {
    echo "CLASS DomPDF: MISSING\n";
}
