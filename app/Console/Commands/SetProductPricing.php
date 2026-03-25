<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class SetProductPricing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:set-pricing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set realistic pricing for all products based on category';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('💰 Setting product pricing...');

        $products = Product::with('category')->get();

        if ($products->isEmpty()) {
            $this->warn('No products found.');

            return Command::FAILURE;
        }

        $this->info("Processing {$products->count()} products...");
        $this->newLine();

        $updated = 0;

        foreach ($products as $product) {
            /** @var Product $product */
            $pricing = $this->calculatePricing($product);

            $product->update([
                'price' => $pricing['price'],
                'sale_price' => $pricing['sale_price'],
            ]);

            $priceFormatted = number_format($pricing['price'], 0, ',', '.');
            $saleInfo = $pricing['sale_price']
                ? ' (Sale: '.number_format($pricing['sale_price'], 0, ',', '.').'đ)'
                : '';

            $this->line("✅ {$product->name}: {$priceFormatted}đ{$saleInfo}");
            $updated++;
        }

        $this->newLine();
        $this->info('📊 Summary:');
        $this->info("   Updated: {$updated} products");
        $this->newLine();
        $this->info('✨ Done! All products now have pricing.');

        return Command::SUCCESS;
    }

    /**
     * Calculate pricing based on product category
     */
    private function calculatePricing(Product $product): array
    {
        $categorySlug = $product->category->slug ?? 'default';

        // Price ranges by category (VND)
        $priceRanges = [
            'ao-thun' => [150000, 250000],
            'vay-dam' => [300000, 500000],
            'quan-jean' => [350000, 450000],
            'phu-kien' => [100000, 300000],
            'default' => [200000, 400000],
        ];

        // Get appropriate range
        $range = $priceRanges[$categorySlug] ?? $priceRanges['default'];

        // Generate random price within range
        $price = rand($range[0], $range[1]);

        // Round to nearest 10,000
        $price = round($price / 10000) * 10000;

        // 70% chance of having sale price
        $salePrice = null;
        if (rand(1, 100) <= 70) {
            // Random discount between 10-30%
            $discountPercent = rand(10, 30);
            $salePrice = $price * (1 - $discountPercent / 100);

            // Round to nearest 10,000
            $salePrice = round($salePrice / 10000) * 10000;
        }

        return [
            'price' => $price,
            'sale_price' => $salePrice,
        ];
    }
}
