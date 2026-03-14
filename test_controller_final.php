<?php
// Standalone Controller Logic Test Script v7
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Frontend\VtonController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

echo "Starting Controller Logic Test v7...\n";

$controller = new VtonController();

// Test the mapping
$product = Product::first();
$cat = (new \ReflectionMethod($controller, 'mapCategory'))->invoke($controller, $product);
echo "Product: " . $product->name . " -> Category: " . $cat . "\n";

// Test the attemptTryOn logic (with real images)
$modelPath = 'C:\laragon\www\elite\storage\app\public\vton-models\DXhBaA2kjBFwO9IfK5iiOiHUoUAI4cX0ZFE8XZTd.jpg';
$garmentPath = 'C:\laragon\www\elite\storage\app\public\products\product1.jpg';

$modelBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($modelPath));
$garmentBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($garmentPath));

echo "Simulating attemptTryOn (might take 60s+)...\n";
$resultUrl = (new \ReflectionMethod($controller, 'attemptTryOn'))->invoke($controller, $modelBase64, $garmentBase64, 'Upper-body');

if ($resultUrl) {
    echo "SUCCESS! Result URL: " . $resultUrl . "\n";
} else {
    echo "FAILED: All spaces returned errors or timed out.\n";
}

echo "\nTest Finished.\n";
