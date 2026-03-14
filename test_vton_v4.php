<?php
// Standalone IDM-VTON Test Script v4
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

echo "Starting IDM-VTON Test v4...\n";

$modelPath = 'C:\laragon\www\elite\storage\app\public\vton-models\DXhBaA2kjBFwO9IfK5iiOiHUoUAI4cX0ZFE8XZTd.jpg';
$garmentPath = 'C:\laragon\www\elite\storage\app\public\products\product1.jpg';

if (!file_exists($modelPath) || !file_exists($garmentPath)) {
    echo "ERROR: Test images not found.\n";
    exit;
}

$modelBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($modelPath));
$garmentBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($garmentPath));

$url = 'https://yisol-idm-vton.hf.space/call/tryon';

$payload = [
    'data' => [
        ['url' => $modelBase64, 'meta' => ['_type' => 'gradio.FileData']],
        ['url' => $garmentBase64, 'meta' => ['_type' => 'gradio.FileData']],
        "Fashion garment", // Prompt
        true,  // is_checked (Auto mask)
        true,  // is_checked_crop (Auto crop)
        30,    // denoise_steps
        42     // seed
    ]
];

echo "Sending request to IDM-VTON...\n";
try {
    $response = Http::timeout(30)->post($url, $payload);
    echo "Initial Response: " . $response->status() . " " . $response->body() . "\n";
    $eventId = $response->json('event_id');
    
    if ($eventId) {
        echo "Event ID: $eventId. Polling...\n";
        $startTime = time();
        while ((time() - $startTime) < 60) {
            $statusRes = Http::timeout(30)->get($url . '/' . $eventId);
            $body = $statusRes->body();
            echo ".";
            if (Str::contains($body, 'event: complete') || Str::contains($body, '"url"')) {
                echo "\nSUCCESS!\n";
                if (preg_match('/"url":\s*"([^"]+)"/', $body, $matches)) {
                    echo "Result URL: " . $matches[1] . "\n";
                }
                break;
            }
            if (Str::contains($body, 'event: error')) {
                echo "\nERROR: " . $body . "\n";
                break;
            }
            sleep(3);
        }
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
echo "\nTest Finished.\n";
