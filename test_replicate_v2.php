<?php
// Standalone Replicate VTON Test Script v2
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

echo "Starting Replicate VTON Test v2...\n";

$replicateToken = env('REPLICATE_API_TOKEN');
if (!$replicateToken) {
    echo "ERROR: REPLICATE_API_TOKEN not found in .env\n";
    exit;
}

$modelPath = 'C:\laragon\www\elite\storage\app\public\vton-models\DXhBaA2kjBFwO9IfK5iiOiHUoUAI4cX0ZFE8XZTd.jpg';
$garmentPath = 'C:\laragon\www\elite\storage\app\public\products\product1.jpg';

$modelBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($modelPath));
$garmentBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($garmentPath));

$url = "https://api.replicate.com/v1/predictions";

$payload = [
    'version' => '9f8fa4956970dde99689af7488157a30aa152e23953526a605df1d77598343d7', 
    'input' => [
        'model_image' => $modelBase64,
        'garment_image' => $garmentBase64,
        'category' => 'Upper-body'
    ]
];

echo "Sending request to Replicate with version 9f8fa495...\n";
try {
    $response = Http::withToken($replicateToken)
        ->timeout(30)
        ->post($url, $payload);
        
    echo "Initial Response Status: " . $response->status() . "\n";
    
    $prediction = $response->json();
    if (isset($prediction['id'])) {
        $predictionId = $prediction['id'];
        echo "Prediction ID: $predictionId. Polling...\n";
        
        $startTime = time();
        while ((time() - $startTime) < 180) { // 3 min max
            $statusRes = Http::withToken($replicateToken)
                ->get("https://api.replicate.com/v1/predictions/$predictionId");
            
            $result = $statusRes->json();
            $status = $result['status'] ?? 'unknown';
            echo "Status: $status\n";
            
            if ($status === 'succeeded') {
                echo "SUCCESS!\n";
                if (is_array($result['output'])) {
                    print_r($result['output']);
                } else {
                    echo "Output: " . $result['output'] . "\n";
                }
                break;
            }
            if ($status === 'failed') {
                echo "FAILED: " . ($result['error'] ?? 'Unknown error') . "\n";
                break;
            }
            sleep(5);
        }
    } else {
        echo "Failed to start prediction. Response: " . $response->body() . "\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
echo "\nTest Finished.\n";
