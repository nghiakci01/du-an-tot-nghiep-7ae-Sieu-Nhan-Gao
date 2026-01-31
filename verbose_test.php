<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ChatbotSetting;
$apiKey = ChatbotSetting::where('key', 'openai_api_key')->value('value');

if (!$apiKey) {
    die("Error: No OpenAI API Key found in settings.\n");
}

echo "Testing connection to api.openai.com v1/responses...\n";
echo "Active API Key ends with: " . substr($apiKey, -5) . "\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/responses");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_STDERR, fopen('php://stdout', 'w'));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => 'gpt-3.5-turbo',
    'instructions' => 'test',
    'input' => [['type' => 'text', 'text' => 'hi']]
]));

// Applied from FIX 1
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

// Force TLS 1.2 for testing
// curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);

$response = curl_exec($ch);
$errNo = curl_errno($ch);
$errMsg = curl_error($ch);

echo "\n--- RESULT ---\n";
if ($errNo) {
    echo "CURL Error ($errNo): $errMsg\n";
} else {
    echo "HTTP Status: " . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\n";
    echo "Response: " . substr($response, 0, 200) . "...\n";
}
curl_close($ch);
