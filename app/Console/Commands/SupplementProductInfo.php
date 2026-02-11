<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class SupplementProductInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:supplement-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-generate descriptions for products missing this data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('📝 Supplementing product information...');

        // Find products missing description or short_description
        $products = Product::with('category')
            ->where(function($query) {
                $query->whereNull('description')
                      ->orWhere('description', '')
                      ->orWhereNull('short_description')
                      ->orWhere('short_description', '');
            })
            ->get();

        if ($products->isEmpty()) {
            $this->info('✅ All products already have complete information.');
            return Command::SUCCESS;
        }

        $this->info("Found {$products->count()} products needing supplementation.");
        $this->newLine();

        $updated = 0;

        foreach ($products as $product) {
            $needsUpdate = false;
            $updates = [];

            // Generate short description if missing
            if (!$product->short_description || strlen($product->short_description) == 0) {
                $updates['short_description'] = $this->generateShortDescription($product);
                $needsUpdate = true;
            }

            // Generate description if missing
            if (!$product->description || strlen($product->description) == 0) {
                $updates['description'] = $this->generateDescription($product);
                $needsUpdate = true;
            }

            if ($needsUpdate) {
                $product->update($updates);
                $this->line("✅ {$product->name}");
                $updated++;
            }
        }

        $this->newLine();
        $this->info("📊 Summary:");
        $this->info("   Updated: {$updated} products");
        $this->newLine();
        $this->info('✨ Done! All products now have complete information.');

        return Command::SUCCESS;
    }

    /**
     * Generate short description for product
     */
    private function generateShortDescription(Product $product): string
    {
        $category = $product->category ? $product->category->name : 'Sản phẩm';
        $price = number_format($product->price, 0, ',', '.');

        return "{$product->name} - {$category} chất lượng cao, giá {$price}đ. Phù hợp cho mọi lứa tuổi, thiết kế hiện đại.";
    }

    /**
     * Generate full description for product
     */
    private function generateDescription(Product $product): string
    {
        $category = $product->category ? $product->category->name : 'Sản phẩm';
        $price = number_format($product->price, 0, ',', '.');
        
        $saleInfo = '';
        if ($product->sale_price && $product->sale_price < $product->price) {
            $salePrice = number_format($product->sale_price, 0, ',', '.');
            $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
            $saleInfo = "\n\n🔥 Giá khuyến mãi: {$salePrice}đ (Tiết kiệm {$discount}%)";
        }

        return <<<DESC
{$product->name} là sản phẩm {$category} được thiết kế với chất liệu cao cấp, đảm bảo độ bền và thoải mái khi sử dụng.

✨ Đặc điểm nổi bật:
• Chất liệu: Cotton/Polyester cao cấp, thấm hút mồ hôi tốt
• Thiết kế: Hiện đại, trẻ trung, phù hợp xu hướng
• Màu sắc: Đa dạng, dễ phối đồ
• Size: S, M, L, XL (vui lòng xem bảng size chi tiết)
• Độ bền: Giữ form tốt sau nhiều lần giặt

💰 Giá: {$price}đ{$saleInfo}

👥 Phù hợp cho: Nam/Nữ, mọi lứa tuổi
🎁 Cam kết: Hàng chính hãng, đổi trả trong 7 ngày

📦 Giao hàng toàn quốc, thanh toán khi nhận hàng (COD)
DESC;
    }
}
