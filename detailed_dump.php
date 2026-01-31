<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ChatbotSetting;
$settings = ChatbotSetting::all();
foreach ($settings as $s) {
    echo "KEY: " . $s->key . "\n";
    echo "VAL: " . substr($s->value, 0, 100) . "\n";
    echo "-------------------\n";
}
