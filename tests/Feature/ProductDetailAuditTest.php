<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductDetailAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a default category to link products to
        $this->category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_404_when_product_does_not_exist()
    {
        $response = $this->get('/product/invalid-slug-12345');
        // Route parameter is slug, so model binding or findOrFail should throw 404
        $response->assertStatus(404);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_renders_product_page_even_if_image_is_null_or_empty()
    {
        $this->withoutExceptionHandling();
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'No Image Product',
            'slug' => 'no-image-product',
            'price' => 100000,
            'image' => null, // Empty image
            'is_active' => true,
        ]);

        $response = $this->get('/product/' . $product->slug);

        $response->assertStatus(200);
        $response->assertSee('No Image Product');
        // It shouldn't crash because we recently fixed the fallback image logic in the view
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_renders_product_page_gracefully_when_price_is_zero()
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Free Product',
            'slug' => 'free-product',
            'price' => 0, // Zero price
            'is_active' => true,
        ]);

        $response = $this->get('/product/' . $product->slug);

        $response->assertStatus(200);
        $response->assertSee('Free Product');
        $response->assertSee('0'); 
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_handles_invalid_pagination_or_filter_queries_gracefully()
    {
        // Go to product list page with garbage inputs
        $response = $this->get('/shop?page=abc&category_id=xyz&min_price=-500&sort=select*from');

        $response->assertStatus(200); // Should just ignore invalid filters and load default list
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_shows_out_of_stock_message_if_no_stock_available()
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Out of Stock Product',
            'slug' => 'out-of-stock-product',
            'price' => 50000,
            'is_active' => true,
        ]);

        // Create a variant with 0 stock
        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'TEST-001',
            'stock_quantity' => 0,
            'price' => 50000,
        ]);

        $response = $this->get('/product/' . $product->slug);

        $response->assertStatus(200);
        
        // At least it shouldn't allow adding to cart normally if totally out of stock
        // We look for 'Hết hàng' or disable add to cart button
        // Note: The assertion might vary based on the actual translation or HTML
        $response->assertSee('Hết hàng');
    }
}
