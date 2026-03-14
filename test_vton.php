<?php
// Standalone VTON Test Script
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

echo "Starting VTON Test...\n";

$hfUrl = 'https://levihsu-ootdiffusion.hf.space/call/process_hd';

// Dummy data for testing (minimal valid base64 or placeholder)
// Using a real image URL from the project if possible
$humanImageBase64 = "https://levihsu-ootdiffusion.hf.space/file=/tmp/gradio/7e1b5f6b2b57e7939768e1694f8da3d540250630/image.png"; // Placeholder model
$productImageBase64 = "https://levihsu-ootdiffusion.hf.space/file=/tmp/gradio/c6f78a7c1b5d1e2e3e4e5f6g7h8i9j0k1l2m3n4o/garment.png"; // Placeholder garment

$payload = [
    'data' => [
        ['url' => 'https://raw.githubusercontent.com/gradio-app/gradio/main/test/test_files/bus.png', 'meta' => ['_type' => 'gradio.FileData']],
        ['url' => 'https://raw.githubusercontent.com/gradio-app/gradio/main/test/test_files/bus.png', 'meta' => ['_type' => 'gradio.FileData']],
        'Upper-body',
        1,   // Model select
        20,  // Steps
        2.0, // Guidance scale
        -1   // Seed
    ]
];

echo "Sending initial request to HF...\n";
try {
    $response = Http::timeout(30)->post($hfUrl, $payload);
    echo "Response status: " . $response->status() . "\n";
    $eventId = $response->json('event_id');
    echo "Event ID: " . ($eventId ?? 'NULL') . "\n";

    if (!$eventId) {
        echo "Failed to get Event ID. Body: " . $response->body() . "\n";
        exit;
    }

    echo "Polling for result...\n";
    $startTime = time();
    $maxWait = 60;
    while ((time() - $startTime) < $maxWait) {
        $statusRes = Http::timeout(30)->get($hfUrl . '/' . $eventId);
        $body = $statusRes->body();
        echo ".";
        
        if (Str::contains($body, 'event: complete') || Str::contains($body, '"url"')) {
            echo "\nFound URL!\n";
            if (preg_match('/"url":\s*"([^"]+)"/', $body, $matches)) {
                $resultUrl = $matches[1];
                echo "Result URL: " . $resultUrl . "\n";
                break;
            }
        }
        
        if (Str::contains($body, 'event: error')) {
            echo "\nEvent Error: " . $body . "\n";
            break;
        }
        sleep(2);
    }
} catch (\Exception $e) {
    echo "\nError: " . $e->getMessage() . "\n";
}
echo "\nTest Finished.\n";
