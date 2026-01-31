<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ChatbotSetting;
$settings = ChatbotSetting::all();
foreach ($settings as $s) {
    if (str_contains($s->key, 'api_key')) {
        echo $s->key . ": " . substr($s->value, 0, 5) . "..." . substr($s->value, -5) . "\n";
    } else {
        echo $s->key . ": " . $s->value . "\n";
    }
}
