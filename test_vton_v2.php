<?php
// Standalone VTON Test Script v2
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

echo "Starting VTON Test v2...\n";

// Using real public images to rule out base64 issues
$humanImageUrl = "https://raw.githubusercontent.com/gradio-app/gradio/main/test/test_files/bus.png"; // Placeholder
$garmentImageUrl = "https://raw.githubusercontent.com/gradio-app/gradio/main/test/test_files/bus.png"; // Placeholder

$endpoints = [
    'https://levihsu-ootdiffusion.hf.space/call/process_hd',
    'https://levihsu-ootdiffusion.hf.space/call/process_dc'
];

foreach ($endpoints as $url) {
    echo "\nTesting Endpoint: $url\n";
    
    $payload = [
        'data' => [
            ['url' => $humanImageUrl, 'meta' => ['_type' => 'gradio.FileData']],
            ['url' => $garmentImageUrl, 'meta' => ['_type' => 'gradio.FileData']],
            'Upper-body',
            1,   // Model select
            20,  // Steps
            2.0, // Guidance scale
            -1   // Seed
        ]
    ];

    try {
        $response = Http::timeout(30)->post($url, $payload);
        echo "Initial Response: " . $response->status() . " " . $response->body() . "\n";
        $eventId = $response->json('event_id');
        
        if ($eventId) {
            echo "Event ID: $eventId. Polling...\n";
            $startTime = time();
            while ((time() - $startTime) < 30) {
                $statusRes = Http::timeout(30)->get($url . '/' . $eventId);
                $body = $statusRes->body();
                echo ".";
                if (Str::contains($body, 'event: complete') || Str::contains($body, '"url"')) {
                    echo "\nSUCCESS!\n";
                    break;
                }
                if (Str::contains($body, 'event: error')) {
                    echo "\nERROR: " . $body . "\n";
                    break;
                }
                sleep(2);
            }
        }
    } catch (\Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n";
    }
}
echo "\nTest Finished.\n";
