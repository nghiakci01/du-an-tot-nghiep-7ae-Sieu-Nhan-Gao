<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\ProductImage;

class CreatePlaceholderImages extends Command
{
    protected $signature = 'images:create-placeholders';
    protected $description = 'Create placeholder images for missing product images in storage';

    public function handle(): int
    {
        $missing = [];

        // Collect missing product images
        Product::whereNotNull('image')->chunk(100, function ($products) use (&$missing) {
            foreach ($products as $product) {
                if (!Storage::disk('public')->exists($product->image)) {
                    $missing[] = $product->image;
                }
            }
        });

        // Collect missing product gallery images
        ProductImage::whereNotNull('image_path')->chunk(100, function ($images) use (&$missing) {
            foreach ($images as $img) {
                if (!Storage::disk('public')->exists($img->image_path)) {
                    $missing[] = $img->image_path;
                }
            }
        });

        $missing = array_unique($missing);
        $this->info("Found " . count($missing) . " missing images. Creating placeholders...");

        $created = 0;
        foreach ($missing as $path) {
            $dir = dirname($path);
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }

            $fullPath = storage_path('app/public/' . $path);
            $this->createPlaceholder($fullPath, basename($path));
            $created++;
        }

        $this->info("Created {$created} placeholder images.");
        $this->info("Storage symlink: " . (file_exists(public_path('storage')) ? '✓ exists' : '✗ missing - run: php artisan storage:link'));

        return self::SUCCESS;
    }

    private function createPlaceholder(string $filePath, string $label): void
    {
        if (!extension_loaded('gd')) {
            // Fallback: copy a default image if GD not available
            $defaultPlaceholder = public_path('frontend-assets/img/product/product21.jpg');
            if (file_exists($defaultPlaceholder)) {
                copy($defaultPlaceholder, $filePath);
            }
            return;
        }

        $width = 600;
        $height = 600;
        $image = imagecreatetruecolor($width, $height);

        // Soft gray background
        $bg = imagecolorallocate($image, 240, 240, 240);
        imagefill($image, 0, 0, $bg);

        // Border
        $border = imagecolorallocate($image, 200, 200, 200);
        imagerectangle($image, 0, 0, $width - 1, $height - 1, $border);

        // Text
        $textColor = imagecolorallocate($image, 150, 150, 150);
        $text = 'No Image';
        imagestring($image, 5, ($width - strlen($text) * 10) / 2, $height / 2 - 10, $text, $textColor);

        imagejpeg($image, $filePath, 85);
        imagedestroy($image);
    }
}
