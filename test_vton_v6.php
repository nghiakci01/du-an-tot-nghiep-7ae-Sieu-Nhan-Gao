<?php
// Standalone VTON Test Script v6 (The REAL Fix?)
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

echo "Starting VTON Test v6 (Correcting Endpoint to process_dc)...\n";

$modelPath = 'C:\laragon\www\elite\storage\app\public\vton-models\DXhBaA2kjBFwO9IfK5iiOiHUoUAI4cX0ZFE8XZTd.jpg';
$garmentPath = 'C:\laragon\www\elite\storage\app\public\products\product1.jpg';

$modelBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($modelPath));
$garmentBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($garmentPath));

// USING process_dc BECAUSE IT HAS 7 INPUTS (including category)
$url = 'https://levihsu-ootdiffusion.hf.space/call/process_dc';

$payload = [
    'data' => [
        ['url' => $modelBase64, 'meta' => ['_type' => 'gradio.FileData']],
        ['url' => $garmentBase64, 'meta' => ['_type' => 'gradio.FileData']],
        'Upper-body', // Category
        1,   // Images
        20,  // Steps
        2.0, // Guidance scale
        -1   // Seed
    ]
];

echo "Sending request to process_dc...\n";
try {
    $response = Http::timeout(30)->post($url, $payload);
    echo "Initial Response: " . $response->status() . " " . $response->body() . "\n";
    $eventId = $response->json('event_id');
    
    if ($eventId) {
        echo "Event ID: $eventId. Polling...\n";
        $startTime = time();
        while ((time() - $startTime) < 120) {
            $statusRes = Http::timeout(60)->get($url . '/' . $eventId);
            $body = $statusRes->body();
            echo ".";
            if (Str::contains($body, 'event: complete')) {
                echo "\nSUCCESS (Complete)!\n";
                // Gradio SSE format: event: complete\ndata: [...]
                if (preg_match('/data:\s*\[\s*\[\s*\{\s*"image":\s*\{\s*"url":\s*"([^"]+)"/', $body, $matches)) {
                   echo "Result URL: " . $matches[1] . "\n";
                } else {
                    echo "Could not find image URL in complete event data.\n";
                    echo "Full body segment: " . substr($body, strpos($body, 'event: complete')) . "\n";
                }
                break;
            }
            if (Str::contains($body, 'event: error')) {
                echo "\nERROR: " . $body . "\n";
                break;
            }
            if (Str::contains($body, '"url"')) {
                echo "\nURL FOUND in Stream!\n";
                 if (preg_match('/"url":\s*"([^"]+)"/', $body, $matches)) {
                    echo "URL: " . $matches[1] . "\n";
                }
            }
            sleep(5);
        }
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
echo "\nTest Finished.\n";
