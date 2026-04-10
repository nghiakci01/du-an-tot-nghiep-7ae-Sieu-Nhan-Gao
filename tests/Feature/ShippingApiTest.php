<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Tests\TestCase;

class ShippingApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 200000,
            'name' => 'Shipping Product ' . Str::random(4),
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'size' => 'M',
            'color' => 'Black',
            'stock_quantity' => 10,
            'price' => 200000,
            'sku' => 'SHIP-' . Str::random(5),
        ]);

        Session::put('cart', [
            (string) $variant->id => [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'name' => $product->name,
                'quantity' => 2,
                'price' => 200000,
            ],
        ]);
        Session::put('selected_checkout_ids', [(string) $variant->id]);
    }

    public function test_shipping_api_returns_ranked_home_delivery_options(): void
    {
        $response = $this->postJson('/api/checkout/shipping-fees', [
            'delivery_type' => 'home',
            'province' => 'HÃ  Ná»™i',
            'district' => 'Ba ÄÃ¬nh',
            'ward' => 'PhÃºc XÃ¡',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.provider', 'ghn');
    }

    public function test_shipping_api_returns_store_pickup_option(): void
    {
        $response = $this->postJson('/api/checkout/shipping-fees', [
            'delivery_type' => 'store',
            'province' => 'HÃ  Ná»™i',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.provider', 'store_pickup')
            ->assertJsonPath('data.0.fee', 0);
    }
}
