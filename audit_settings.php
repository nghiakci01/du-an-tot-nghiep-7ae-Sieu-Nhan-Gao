<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ChatbotSetting;
$settings = ChatbotSetting::all();
foreach ($settings as $s) {
    echo "ID: " . $s->id . " | KEY: " . $s->key . " | LEN: " . strlen($s->value) . " | VAL: " . str_replace("\n", " ", substr($s->value, 0, 30)) . "\n";
}
