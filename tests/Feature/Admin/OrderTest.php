<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_orders_list()
    {
        Order::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertViewHas('orders');
    }

    public function test_admin_can_create_manual_order()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'size' => 'XL',
            'color' => 'White',
            'stock_quantity' => 10,
            'price' => 200000,
            'sku' => 'ADMIN-ORDER-SKU'
        ]);

        $data = [
            'customer_type' => 'NEW',
            'name' => 'Admin Guest',
            'phone' => '0987654321',
            'email' => 'admin_guest@example.com',
            'province' => 'Hải Phòng',
            'address' => '456 Admin St',
            'items' => [
                [
                    'variant_id' => $variant->id,
                    'quantity' => 2
                ]
            ],
            'payment_method' => 'CASH',
            'status' => Order::STATUS_CONFIRMED,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.orders.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'name' => 'Admin Guest',
            'status' => Order::STATUS_CONFIRMED
        ]);

        $this->assertEquals(8, $variant->fresh()->stock_quantity);
    }
}
