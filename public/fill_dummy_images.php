<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$dummyImage = __DIR__.'/frontend-assets/img/product/product21.jpg';

// Helper function
function ensureImageExists($path, $dummy) {
    if (empty($path)) return;
    
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        copy($dummy, $fullPath);
        echo "Created missing image: $path\n";
    }
}

// 1. Products
$products = App\Models\Product::all();
foreach ($products as $product) {
    ensureImageExists($product->image, $dummyImage);
}

// 2. Product Images (Gallery)
$gallery = App\Models\ProductImage::all();
foreach ($gallery as $image) {
    ensureImageExists($image->image_path, $dummyImage);
}

// 3. User Avatars
$users = App\Models\User::all();
foreach ($users as $user) {
    if (!empty($user->avatar) && strpos($user->avatar, 'http') === false) {
        ensureImageExists($user->avatar, $dummyImage);
    }
}

echo "Image syncing completed successfully.\n";
