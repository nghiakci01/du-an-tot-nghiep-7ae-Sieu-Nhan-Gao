<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

$mapping = [
    2 => ['product1.jpg', 'product2.jpg', 'product3.jpg'],
    3 => ['product4.jpg', 'product5.jpg', 'product6.jpg'],
    4 => ['product7.jpg', 'product8.jpg', 'product9.jpg'],
    5 => ['product10.jpg', 'product11.jpg', 'product12.jpg'],
];

foreach ($mapping as $productId => $images) {
    $product = Product::find($productId);
    if (!$product) continue;

    echo "Processing Product ID: $productId ($product->name)\n";

    foreach ($images as $index => $imageName) {
        $sourcePath = public_path("frontend-assets/img/product/$imageName");
        if (!file_exists($sourcePath)) {
            echo "  Source image not found: $imageName\n";
            continue;
        }

        // Generate a new unique filename
        $extension = pathinfo($imageName, PATHINFO_EXTENSION);
        $newFilename = Str::random(40) . ".$extension";
        $targetPath = "products/gallery/$newFilename";

        // Copy to storage
        Storage::disk('public')->put($targetPath, file_get_contents($sourcePath));

        // Create DB record
        $product->images()->create([
            'image_path' => $targetPath,
            'sort_order' => $product->images()->count()
        ]);

        echo "  Added gallery image: $imageName -> $targetPath\n";
    }
}
echo "Done!\n";
