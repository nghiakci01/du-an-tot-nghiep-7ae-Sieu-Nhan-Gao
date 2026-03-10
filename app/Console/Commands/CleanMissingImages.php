<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanMissingImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:clean-missing-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean products and gallery images with missing image files from storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Scanning main product images for missing files...');

        $products = Product::whereNotNull('image')->get();
        $productsCleaned = 0;
        $totalProducts = $products->count();

        foreach ($products as $product) {
            // Check if absolute path (contaminated data)
            if (str_starts_with($product->image, 'C:') || str_starts_with($product->image, '/')) {
                $this->warn("Found absolute path in product ID {$product->id}: {$product->image}. Clearing.");
                $product->update(['image' => null]);
                $productsCleaned++;

                continue;
            }

            if (! Storage::disk('public')->exists($product->image)) {
                $this->warn("Missing main image: {$product->image} (Product: {$product->name})");
                $product->update(['image' => null]);
                $productsCleaned++;
            }
        }

        $this->info("✅ Cleaned {$productsCleaned} main images. ".($totalProducts - $productsCleaned).' remain valid.');

        $this->newLine();
        $this->info('Scanning gallery images for missing files...');

        $galleryImages = ProductImage::all();
        $galleryCleaned = 0;
        $totalGallery = $galleryImages->count();

        foreach ($galleryImages as $galleryImage) {
            // Check if absolute path (contaminated data)
            if (str_starts_with($galleryImage->image_path, 'C:') || str_starts_with($galleryImage->image_path, '/')) {
                $this->warn("Found absolute path in gallery image (ID: {$galleryImage->id}): {$galleryImage->image_path}. Clearing.");
                $galleryImage->delete();
                $galleryCleaned++;

                continue;
            }

            if (! Storage::disk('public')->exists($galleryImage->image_path)) {
                $this->warn("Missing gallery image: {$galleryImage->image_path} (ID: {$galleryImage->id})");
                $galleryImage->delete();
                $galleryCleaned++;
            }
        }

        $this->info("✅ Cleaned {$galleryCleaned} gallery images. ".($totalGallery - $galleryCleaned).' remain valid.');

        return Command::SUCCESS;
    }
}
