<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Tests\TestCase;

class CheckoutAuditTest extends TestCase
{
    use RefreshDatabase;

    protected string $province;

    protected function setUp(): void
    {
        parent::setUp();

        $this->province = config('vietnam_provinces')[23];

        Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true,
        ]);

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
            ->assertSessionHas('error');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function checkout_page_redirects_to_cart_if_item_is_out_of_stock()
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id, 0);

        $cart = [
            $variant->id => [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
            ],
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

        $cart = [
            $variant->id => [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'name' => $product->name,
                'quantity' => 2,
                'price' => 200000,
            ],
        ];
        Session::put('cart', $cart);
        Session::put('selected_checkout_ids', [$variant->id]);

        $response = $this->post('/checkout', [
            'name' => 'John Doe',
            'phone' => '0912345678',
            'email' => 'john@gmail.com',
            'province' => $this->province,
            'address' => '123 Fake Street',
            'delivery_type' => 'home',
            'payment_method' => 'COD',
            'shipping_provider' => 'ghtk',
        ]);

        $order = Order::latest()->first();

        $this->assertNotNull($order);
        $response->assertRedirect(route('checkout.success', $order->id));
        $this->assertEquals('John Doe', $order->name);
        $this->assertEquals(418000.0, (float) $order->final_total);

        $variant->refresh();
        $this->assertEquals(8, $variant->stock_quantity);
        $this->assertEmpty(Session::get('cart'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_calculates_total_correctly_when_valid_coupon_is_applied()
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id, 10);

        $cart = [
            $variant->id => [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'name' => $product->name,
                'quantity' => 1,
                'price' => 200000,
            ],
        ];
        Session::put('cart', $cart);
        Session::put('selected_checkout_ids', [$variant->id]);
        Session::put('coupon_code', 'TEST50');
        Session::put('discount_amount', 50000);

        $response = $this->post('/checkout', [
            'name' => 'Jane Doe',
            'phone' => '0987654321',
            'email' => 'jane@gmail.com',
            'province' => $this->province,
            'address' => '456 Fake Ave',
            'delivery_type' => 'home',
            'payment_method' => 'COD',
            'shipping_provider' => 'ghtk',
        ]);

        $order = Order::latest()->first();

        $this->assertNotNull($order);
        $response->assertRedirect(route('checkout.success', $order->id));
        $this->assertEquals(168000.0, (float) $order->final_total);
        $this->assertEquals(50000.0, (float) $order->discount_amount);
        $this->assertEquals('TEST50', $order->coupon_code);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_supports_store_pickup_without_address_fields()
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id, 5);

        Session::put('cart', [
            $variant->id => [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'name' => $product->name,
                'quantity' => 1,
                'price' => 200000,
            ],
        ]);
        Session::put('selected_checkout_ids', [$variant->id]);

        $response = $this->post('/checkout', [
            'name' => 'Pickup User',
            'phone' => '0911111111',
            'email' => 'pickup@gmail.com',
            'delivery_type' => 'store',
            'payment_method' => 'COD',
            'shipping_provider' => 'store_pickup',
        ]);

        $order = Order::latest()->first();

        $this->assertNotNull($order);
        $response->assertRedirect(route('checkout.success', $order->id));
        $this->assertEquals(0.0, (float) $order->shipping_fee);
        $this->assertEquals('store_pickup', $order->shipping_provider);
        $this->assertEquals('Nhan tai cua hang', $order->address);
    }
}
