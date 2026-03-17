<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CartAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure standard category exists for tests
        Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true,
        ]);
    }

    private function createProduct()
    {
        $category = Category::first();
        return Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'slug' => 'test-product-' . Str::random(5),
            'price' => 100000,
            'sku' => 'PROD-' . Str::random(5),
            'is_active' => true,
        ]);
    }

    private function createVariant($productId, $stock, $size = 'S', $price = null)
    {
        return ProductVariant::create([
            'product_id' => $productId,
            'size' => $size,
            'color' => 'Red',
            'stock_quantity' => $stock,
            'price' => $price,
            'sku' => 'SKU-' . Str::random(5),
        ]);
    }

    /** @test */
    public function it_requires_variant_selection_if_product_has_multiple_variants()
    {
        $product = $this->createProduct();
        
        $this->createVariant($product->id, 10, 'S');
        $this->createVariant($product->id, 10, 'M');

        $response = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(422)
                 ->assertJsonPath('success', false)
                 ->assertJsonPath('message', 'Vui lòng chọn kích thước và màu sắc trước khi thêm vào giỏ hàng.');
    }

    /** @test */
    public function it_auto_selects_variant_if_product_has_only_one_variant()
    {
        $product = $this->createProduct();
        
        $variant = $this->createVariant($product->id, 10);

        $response = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('success', true);

        $cart = session('cart');
        $this->assertNotNull($cart);
        $this->assertArrayHasKey($variant->id, $cart);
        $this->assertEquals(2, $cart[$variant->id]['quantity']);
    }

    /** @test */
    public function it_prevents_adding_more_than_stock_quantity()
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id, 5);

        $response = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 6, // Exceeds stock of 5
        ]);

        $response->assertStatus(422)
                 ->assertJsonPath('success', false);
    }

    /** @test */
    public function it_prevents_adding_if_existing_cart_quantity_plus_new_quantity_exceeds_stock()
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id, 5);

        // Add 3 first
        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        // Try to add 3 more (total 6 > stock 5)
        $response = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        $response->assertStatus(422)
                 ->assertJsonPath('success', false);
                 
        $cart = session('cart');
        $this->assertEquals(3, $cart[$variant->id]['quantity']);
    }

    /** @test */
    public function it_accumulates_quantity_when_added_multiple_times()
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id, 20);

        // Add 2
        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        // Add 3
        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        $cart = session('cart');
        $this->assertEquals(5, $cart[$variant->id]['quantity']);
    }

    /** @test */
    public function it_can_update_cart_quantity()
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id, 20, 'S', 100000);

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response = $this->patchJson('/cart/update', [
            'id' => $variant->id,
            'quantity' => 5,
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('success', true);

        $cart = session('cart');
        $this->assertEquals(5, $cart[$variant->id]['quantity']);
    }

    /** @test */
    public function it_can_remove_item_from_cart()
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id, 20);

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response = $this->postJson('/cart/remove', [
            'id' => $variant->id,
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('success', true);

        $cart = session('cart', []);
        $this->assertArrayNotHasKey($variant->id, $cart);
    }
}
