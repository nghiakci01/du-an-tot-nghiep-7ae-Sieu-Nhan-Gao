<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class UpdateProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update product images with theme assets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🖼️  Updating product images with theme assets...');

        // Get all products
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->warn('No products found in database.');

            return Command::FAILURE;
        }

        // Available theme images (product1.jpg to product28.jpg)
        $availableImages = [];
        for ($i = 1; $i <= 28; $i++) {
            $availableImages[] = "products/product{$i}.jpg";
        }

        $updated = 0;
        $imageIndex = 0;

        foreach ($products as $product) {
            // Cycle through available images
            $imagePath = $availableImages[$imageIndex % count($availableImages)];

            $product->update(['image' => $imagePath]);
            $this->line("✅ {$product->name} → {$imagePath}");

            $updated++;
            $imageIndex++;
        }

        $this->newLine();
        $this->info('📊 Summary:');
        $this->info("   Total products: {$products->count()}");
        $this->info("   Updated: {$updated} products");
        $this->newLine();
        $this->info('✨ Done! All products now have theme images.');

        return Command::SUCCESS;
    }
}
