<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ChatbotSetting;
$settings = ChatbotSetting::all();
echo "ID | KEY | VALUE\n";
foreach ($settings as $s) {
    echo $s->id . " | " . $s->key . " | " . str_replace("\n", "[NL]", substr($s->value, 0, 50)) . "...\n";
}
