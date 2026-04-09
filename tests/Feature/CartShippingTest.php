<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Tests\TestCase;

class CartShippingTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_update_uses_default_address_shipping_quote(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        UserAddress::create([
            'user_id' => $user->id,
            'receiver_name' => 'Nguyen Van A',
            'phone' => '0912345678',
            'province' => 'Ha Noi',
            'commune' => 'Phuc Xa',
            'address' => '123 Test Street',
            'is_default' => true,
        ]);

        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 100000,
            'name' => 'Cart Shipping Product ' . Str::random(4),
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'size' => 'M',
            'color' => 'Black',
            'stock_quantity' => 10,
            'price' => 100000,
            'sku' => 'CART-' . Str::random(5),
        ]);

        Session::put('cart', [
            (string) $variant->id => [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'name' => $product->name,
                'quantity' => 1,
                'price' => 100000,
                'image' => $product->image,
                'slug' => $product->slug,
            ],
        ]);

        $response = $this->actingAs($user)->patchJson(route('cart.update'), [
            'id' => $variant->id,
            'quantity' => 2,
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('cart_total', '200,000 đ')
            ->assertJsonPath('shipping_fee', '22,000 đ')
            ->assertJsonPath('grand_total', '222,000 đ');
    }
}
