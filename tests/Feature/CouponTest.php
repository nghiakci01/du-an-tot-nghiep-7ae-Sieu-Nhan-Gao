<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup product in cart to test coupon
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'price' => 10000]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'size' => 'M',
            'color' => 'Black',
            'stock_quantity' => 10,
            'price' => 10000,
            'sku' => 'TEST-SKU'
        ]);

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 1
        ]);
    }

    public function test_can_apply_valid_coupon()
    {
        $coupon = Coupon::create([
            'code' => 'DISCOUNT10',
            'type' => 'percentage',
            'value' => 10,
            'is_active' => true,
            'min_order_amount' => 5000,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay()
        ]);

        $response = $this->postJson(route('cart.apply_coupon'), ['coupon_code' => 'DISCOUNT10']);

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('data.coupon_code', 'DISCOUNT10');
        
        $this->assertEquals('DISCOUNT10', session('coupon_code'));
    }

    public function test_cannot_apply_invalid_coupon()
    {
        $response = $this->postJson(route('cart.apply_coupon'), ['coupon_code' => 'INVALID_CODE']);

        $response->assertStatus(404)
                 ->assertJsonPath('success', false);
    }

    public function test_cannot_apply_expired_coupon()
    {
        $coupon = Coupon::create([
            'code' => 'EXPIRED',
            'type' => 'percentage',
            'value' => 10,
            'is_active' => true,
            'end_date' => now()->subDay()
        ]);

        $response = $this->postJson(route('cart.apply_coupon'), ['coupon_code' => 'EXPIRED']);

        $response->assertStatus(400)
                 ->assertJsonPath('success', false)
                 ->assertJsonPath('message', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
    }

    public function test_cannot_apply_coupon_below_min_amount()
    {
        $coupon = Coupon::create([
            'code' => 'BIGSPENDER',
            'type' => 'fixed',
            'value' => 100,
            'is_active' => true,
            'min_order_amount' => 50000 // Cart total is only 10000
        ]);

        $response = $this->postJson(route('cart.apply_coupon'), ['coupon_code' => 'BIGSPENDER']);

        $response->assertStatus(400)
                 ->assertJsonPath('success', false)
                 ->assertJsonFragment(['message' => 'Đơn hàng tối thiểu ' . number_format(50000) . ' đ để sử dụng mã này.']);
    }
}
