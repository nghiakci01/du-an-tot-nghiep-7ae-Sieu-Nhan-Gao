<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

try {
    Mail::raw('Test email from Elite application.', function ($message) {
        $message->to('elite22326@gmail.com')->subject('Elite Application Mail Test');
    });
    echo "Mail sent successfully at " . date('Y-m-d H:i:s') . "\n";
} catch (\Exception $e) {
    echo "Mail sending failed: " . $e->getMessage() . "\n";
    Log::error("Mail Test Error: " . $e->getMessage());
}
