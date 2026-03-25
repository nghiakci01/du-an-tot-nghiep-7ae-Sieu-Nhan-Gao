<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Support\Facades\Session;

class CheckoutAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true,
        ]);
        
        // Ensure there is a shipping setting if fallback used
        Setting::create([
            'key' => 'shipping_fee',
            'value' => 30000,
            'type' => 'number',
        ]);
    }

    private function createProduct()
    {
        $category = Category::first();
        return Product::create([
            'category_id' => $category->id,
            'name' => 'Checkout Product',
            'slug' => 'checkout-product-' . Str::random(5),
            'price' => 200000,
            'sku' => 'CHK-' . Str::random(5),
            'is_active' => true,
        ]);
    }

    private function createVariant($productId, $stock, $size = 'S', $price = null)
    {
        return ProductVariant::create([
            'product_id' => $productId,
            'size' => $size,
            'color' => 'Blue',
            'stock_quantity' => $stock,
            'price' => $price,
            'sku' => 'VSKU-' . Str::random(5),
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function checkout_page_redirects_to_cart_if_cart_is_empty()
    {
        $response = $this->get('/checkout');

        $response->assertRedirect(route('cart.index'))
                 ->assertSessionHas('error', 'Vui lòng chọn sản phẩm trong giỏ hàng trước khi thanh toán.');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function checkout_page_redirects_to_cart_if_item_is_out_of_stock()
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id, 0); // Out of stock

        // Simulate cart array
        $cart = [
            $variant->id => [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
            ]
        ];
        Session::put('cart', $cart);

        $response = $this->get('/checkout');

        $response->assertRedirect(route('cart.index'))
                 ->assertSessionHas('error');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_successfully_places_cod_order_deducts_stock_and_clears_cart()
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id, 10);

        // Pre-fill cart
        $cart = [
            $variant->id => [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'name' => $product->name,
                'quantity' => 2,
                'price' => 200000,
            ]
        ];
        Session::put('cart', $cart);
        Session::put('selected_checkout_ids', [$variant->id]);

        $response = $this->post('/checkout', [
            'name' => 'John Doe',
            'phone' => '0912345678',
            'email' => 'john@gmail.com',
            'province' => 'Hà Nội',
            'address' => '123 Fake Street',
            'payment_method' => 'COD',
            'shipping_fee' => 30000,
        ]);

        $order = Order::latest()->first();

        // 1. Should redirect to success
        $response->assertRedirect(route('checkout.success', $order->id));

        // 2. Order created correctly
        $this->assertNotNull($order);
        $this->assertEquals('John Doe', $order->name);
        $this->assertEquals(430000, $order->final_total); // 2 * 200k + 30k ship

        // 3. Stock deducted
        $variant->refresh();
        $this->assertEquals(8, $variant->stock_quantity); // Started with 10, minus 2

        // 4. Cart cleared
        $this->assertEmpty(Session::get('cart'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_calculates_total_correctly_when_valid_coupon_is_applied()
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id, 10);

        // Pre-fill cart
        $cart = [
            $variant->id => [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'name' => $product->name,
                'quantity' => 1,
                'price' => 200000,
            ]
        ];
        Session::put('cart', $cart);
        Session::put('selected_checkout_ids', [$variant->id]);
        
        // Applied 50k discount
        Session::put('coupon_code', 'TEST50');
        Session::put('discount_amount', 50000);

        $response = $this->post('/checkout', [
            'name' => 'Jane Doe',
            'phone' => '0987654321',
            'email' => 'jane@gmail.com',
            'province' => 'Hà Nội',
            'address' => '456 Fake Ave',
            'payment_method' => 'COD',
            'shipping_fee' => 20000,
        ]);

        $order = Order::latest()->first();
        
        $this->assertNotNull($order);
        $this->assertEquals(170000, $order->final_total); // 200k - 50k + 20k
        $this->assertEquals(50000, $order->discount_amount);
        $this->assertEquals('TEST50', $order->coupon_code);
    }
}
