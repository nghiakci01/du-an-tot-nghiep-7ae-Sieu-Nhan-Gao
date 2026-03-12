<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$dummySource = __DIR__.'/frontend-assets/img/product/product21.jpg';
if (!file_exists($dummySource)) {
    echo "Source missing\n"; exit;
}
$dummyHash = md5_file($dummySource);

$deletedGalleryCount = 0;
$nullifiedProductCount = 0;
$deletedFilesCount = 0;

// 1. Gallery Images
$galleryImages = App\Models\ProductImage::all();
foreach ($galleryImages as $imgModel) {
    if (empty($imgModel->image_path)) continue;
    
    $fullPath = storage_path('app/public/' . $imgModel->image_path);
    if (!file_exists($fullPath)) {
        $imgModel->delete();
        $deletedGalleryCount++;
    } else {
        if (md5_file($fullPath) === $dummyHash) {
            unlink($fullPath);
            $imgModel->delete();
            $deletedGalleryCount++;
            $deletedFilesCount++;
        }
    }
}

// 2. Main Product Images
$products = App\Models\Product::all();
foreach ($products as $product) {
    if (empty($product->image)) continue;
    
    $fullPath = storage_path('app/public/' . $product->image);
    if (!file_exists($fullPath)) {
        $product->update(['image' => null]);
        $nullifiedProductCount++;
    } else {
        if (md5_file($fullPath) === $dummyHash) {
            unlink($fullPath);
            $product->update(['image' => null]);
            $nullifiedProductCount++;
            $deletedFilesCount++;
        }
    }
}

// 3. User Avatars
$users = App\Models\User::all();
foreach ($users as $user) {
    if (empty($user->avatar) || strpos($user->avatar, 'http') !== false) continue;
    
    $fullPath = storage_path('app/public/' . $user->avatar);
    if (!file_exists($fullPath)) {
        $user->update(['avatar' => null]);
    } else {
        if (md5_file($fullPath) === $dummyHash) {
            unlink($fullPath);
            $user->update(['avatar' => null]);
            $deletedFilesCount++;
        }
    }
}

echo "Cleanup complete.\n";
echo "- Deleted Gallery DB Records: $deletedGalleryCount\n";
echo "- Nullified Product Images: $nullifiedProductCount\n";
echo "- Deleted Dummy Physical Files: $deletedFilesCount\n";
