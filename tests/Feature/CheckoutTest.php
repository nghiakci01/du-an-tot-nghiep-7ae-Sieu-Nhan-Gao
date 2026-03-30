<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $variant;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'user']);
        
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'price' => 100000]);
        $this->variant = ProductVariant::create([
            'product_id' => $product->id,
            'size' => 'L',
            'color' => 'Blue',
            'stock_quantity' => 10,
            'price' => 100000,
            'sku' => 'CH-SKU-1'
        ]);

        // Add to cart
        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'variant_id' => $this->variant->id,
            'quantity' => 2
        ]);
        
        // Mock session selected items as required by CheckoutController
        session(['selected_checkout_ids' => [(string)$this->variant->id]]);
    }

    public function test_user_can_access_checkout_page()
    {
        $response = $this->actingAs($this->user)->get(route('checkout.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('frontend.checkout.index');
        $response->assertViewHas('cart');
    }

    public function test_user_can_place_cod_order()
    {
        $data = [
            'name' => 'John Doe',
            'phone' => '0381234567',
            'email' => 'john@gmail.com', // Sử dụng gmail để tránh lỗi dns validation nếu có
            'province' => 'Hà Nội',
            'address' => '123 Fake St',
            'payment_method' => 'COD',
            'shipping_fee' => 30000,
        ];

        $initialStock = $this->variant->stock_quantity;

        $response = $this->actingAs($this->user)->post(route('checkout.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'name' => 'John Doe',
            'phone' => '0381234567',
            'payment_method' => 'COD'
        ]);

        // Verify stock was reduced
        $this->assertEquals($initialStock - 2, $this->variant->fresh()->stock_quantity);
    }

    public function test_checkout_fails_if_stock_insufficient()
    {
        // Reduce stock in DB behind the scenes
        $this->variant->update(['stock_quantity' => 1]);

        $data = [
            'name' => 'John Doe',
            'phone' => '0381234567',
            'email' => 'john@gmail.com',
            'province' => 'Hà Nội',
            'address' => '123 Fake St',
            'payment_method' => 'COD',
        ];

        $response = $this->actingAs($this->user)->post(route('checkout.store'), $data);

        $response->assertSessionHas('error');
        $this->assertDatabaseEmpty('orders');
    }
}
