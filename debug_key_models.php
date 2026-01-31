<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiKey = "AIzaSyARg7Etgi2608_EU7jyDuEDfgVOIJOUEJo";

function checkGemini($version, $key) {
    echo "--- $version ---\n";
    $url = "https://generativelanguage.googleapis.com/$version/models?key=$key";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($status !== 200) {
        echo "Error ($status): $res\n";
        return;
    }
    
    $data = json_decode($res, true);
    if (isset($data['models'])) {
        foreach ($data['models'] as $m) {
            echo "- " . $m['name'] . "\n";
        }
    } else {
        echo "No models found.\n";
    }
}

checkGemini('v1', $apiKey);
echo "\n";
checkGemini('v1beta', $apiKey);
