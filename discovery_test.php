<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiKey = "AIzaSyARg7Etgi2608_EU7jyDuEDfgVOIJOUEJo";

function testModel($model, $key) {
    echo "Testing $model ... ";
    $url = "https://generativelanguage.googleapis.com/v1beta/$model:generateContent?key=$key";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'contents' => [['parts' => [['text' => 'hi']]]]
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $res = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($status === 200) {
        echo "SUCCESS ✅\n";
        return true;
    } else {
        echo "FAILED ($status)\n";
        return false;
    }
}

$url = "https://generativelanguage.googleapis.com/v1beta/models?key=$apiKey";
$res = @file_get_contents($url);
if ($res) {
    $data = json_decode($res, true);
    if (isset($data['models'])) {
        foreach ($data['models'] as $m) {
            $name = $m['name'];
            if (in_array('generateContent', $m['supportedGenerationMethods'])) {
                if (testModel($name, $apiKey)) {
                    echo "FOUND WORKING MODEL: $name\n";
                    // break; // Keep going to find all working ones
                }
            }
        }
    }
}
