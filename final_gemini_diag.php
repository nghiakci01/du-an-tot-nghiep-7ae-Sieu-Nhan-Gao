<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiKey = "AIzaSyARg7Etgi2608_EU7jyDuEDfgVOIJOUEJo";

echo "--- LISTING MODELS FOR KEY ---\n";
$url = "https://generativelanguage.googleapis.com/v1beta/models?key=$apiKey";
$res = @file_get_contents($url);

if ($res === false) {
    die("Error: Could not connect to API. Check key permissions.\n");
}

$data = json_decode($res, true);
if (isset($data['models'])) {
    foreach ($data['models'] as $m) {
        $name = $m['name'];
        if (strpos($name, 'models/gemini-') === 0) {
            echo "- " . $name . "\n";
        }
    }
} else {
    echo "No models found. Response:\n";
    print_r($data);
}
