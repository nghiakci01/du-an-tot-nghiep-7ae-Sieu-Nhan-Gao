<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CartValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(\Database\Seeders\SettingSeeder::class);

        Category::create([
            'name' => 'Electronic',
            'slug' => 'electronic',
            'is_active' => true,
        ]);
    }

    private function createProduct()
    {
        return Product::create([
            'category_id' => Category::first()->id,
            'name' => 'Gadget',
            'slug' => 'gadget-' . Str::random(5),
            'price' => 50000,
            'is_active' => true,
        ]);
    }

    private function createVariant($productId)
    {
        return ProductVariant::create([
            'product_id' => $productId,
            'size' => 'M',
            'color' => 'Black',
            'stock_quantity' => 10,
            'price' => 50000,
            'sku' => 'SKU-' . Str::random(5),
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_marks_cart_item_as_deleted_when_product_is_removed()
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id);

        // Add to cart
        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        // Delete product from DB
        $product->delete();

        $response = $this->get('/cart');
        $response->assertStatus(200);
        
        // Check if enriched cart data contains status flags
        $cart = $response->viewData('cart');
        $this->assertArrayHasKey($variant->id, $cart);
        $this->assertTrue($cart[$variant->id]['is_deleted'] ?? false);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_marks_cart_item_as_inactive_when_product_is_deactivated()
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id);

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        // Deactivate product
        $product->update(['is_active' => false]);

        $response = $this->get('/cart');
        $response->assertStatus(200);
        
        $cart = $response->viewData('cart');
        $this->assertTrue($cart[$variant->id]['is_inactive'] ?? false);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_prevents_checkout_with_deleted_products()
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id);

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $product->delete();

        // Try to validate cart for checkout
        $response = $this->getJson('/checkout/validate?ids=' . $variant->id);
        
        $response->assertStatus(200)
                 ->assertJsonPath('valid', false);
    }
}
