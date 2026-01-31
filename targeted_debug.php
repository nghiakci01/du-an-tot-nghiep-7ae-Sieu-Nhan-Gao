<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiKey = "AIzaSyARg7Etgi2608_EU7jyDuEDfgVOIJOUEJo";

function debugModels($version, $key) {
    echo "--- Version: $version ---\n";
    $url = "https://generativelanguage.googleapis.com/$version/models?key=$key";
    $res = @file_get_contents($url);
    if ($res === false) {
        echo "Failed to connect to $version\n";
        return;
    }
    $data = json_decode($res, true);
    if (!isset($data['models'])) {
        echo "No models found in $version\n";
        return;
    }
    
    $found15 = false;
    foreach($data['models'] as $m) {
        $name = $m['name'];
        if (stripos($name, 'gemini-1.5-flash') !== false) {
            echo "MATCH FOUND: $name\n";
            $found15 = true;
        }
    }
    
    if (!$found15) {
        echo "Gemini 1.5 Flash NOT FOUND in $version.\n";
        echo "Available Gemini models in $version:\n";
        foreach($data['models'] as $m) {
            if (stripos($m['name'], 'gemini') !== false) {
                echo "  - " . $m['name'] . "\n";
            }
        }
    }
}

debugModels('v1', $apiKey);
echo "\n";
debugModels('v1beta', $apiKey);
