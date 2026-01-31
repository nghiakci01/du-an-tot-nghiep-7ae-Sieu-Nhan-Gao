<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ChatbotSetting;
$key = ChatbotSetting::where('key', 'openai_api_key')->value('value');
echo "Key: " . $key . "\n";
echo "Hex: " . bin2hex($key) . "\n";
