<?php
// Standalone Replicate VTON Test Script
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

echo "Starting Replicate VTON Test...\n";

$replicateToken = env('REPLICATE_API_TOKEN');
if (!$replicateToken) {
    echo "ERROR: REPLICATE_API_TOKEN not found in .env\n";
    exit;
}

$modelPath = 'C:\laragon\www\elite\storage\app\public\vton-models\DXhBaA2kjBFwO9IfK5iiOiHUoUAI4cX0ZFE8XZTd.jpg';
$garmentPath = 'C:\laragon\www\elite\storage\app\public\products\product1.jpg';

// Replicate usually wants URLs or Base64 for file inputs
// However, many Replicate models accept data URIs
$modelBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($modelPath));
$garmentBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($garmentPath));

$url = "https://api.replicate.com/v1/predictions";

$payload = [
    'version' => 'eb8da275-5dcd-45a8-9993-4a11c8882563', // A common OOTDiffusion version on Replicate (placeholder, need to verify)
    'input' => [
        'model_image' => $modelBase64,
        'garment_image' => $garmentBase64,
        'category' => 'Upper-body'
    ]
];

// Note: I should verify the latest version hash for oot_diffusion on Replicate
// But let's try the generic "model" endpoint if possible, or a known version.
// Using viktorfa/oot_diffusion
$modelIdentifier = "viktorfa/oot_diffusion";

echo "Sending request to Replicate...\n";
try {
    $response = Http::withToken($replicateToken)
        ->timeout(30)
        ->post($url, $payload);
        
    echo "Initial Response: " . $response->status() . " " . $response->body() . "\n";
    
    $prediction = $response->json();
    if (isset($prediction['id'])) {
        $predictionId = $prediction['id'];
        echo "Prediction ID: $predictionId. Polling...\n";
        
        $startTime = time();
        while ((time() - $startTime) < 120) {
            $statusRes = Http::withToken($replicateToken)
                ->get("https://api.replicate.com/v1/predictions/$predictionId");
            
            $result = $statusRes->json();
            $status = $result['status'];
            echo "Status: $status\n";
            
            if ($status === 'succeeded') {
                echo "SUCCESS!\n";
                print_r($result['output']);
                break;
            }
            if ($status === 'failed') {
                echo "FAILED: " . ($result['error'] ?? 'Unknown error') . "\n";
                break;
            }
            sleep(5);
        }
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
echo "\nTest Finished.\n";
