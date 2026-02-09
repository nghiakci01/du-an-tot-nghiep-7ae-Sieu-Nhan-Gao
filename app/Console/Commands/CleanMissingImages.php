<?php

namespace App\Console\Commands;

use App\Models\Product;
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
    protected $description = 'Clean products with missing image files from storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Scanning products for missing images...');
        
        $products = Product::whereNotNull('image')->get();
        $cleaned = 0;
        $total = $products->count();
        
        $this->info("Found {$total} products with images.");
        
        foreach ($products as $product) {
            if (!Storage::disk('public')->exists($product->image)) {
                $this->warn("Missing: {$product->image} (Product: {$product->name})");
                
                // Set image to null
                $product->update(['image' => null]);
                $cleaned++;
            }
        }
        
        $this->newLine();
        $this->info("✅ Cleaned {$cleaned} products with missing images.");
        $this->info("✅ {$total - $cleaned} products have valid images.");
        
        return Command::SUCCESS;
    }
}
