<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturnRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderReturnTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_partial_return_request()
    {
        $user = User::factory()->create();
        
        $category = Category::create(['name' => 'IT', 'slug' => 'it', 'is_active' => true]);
        
        // Create product and variant
        $product = Product::create([
            'name' => 'Laptop', 
            'slug' => 'laptop', 
            'price' => 1000, 
            'is_active' => true, 
            'category_id' => $category->id
        ]);
        
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'variant_name' => 'Silver',
            'price' => 1000,
            'stock_quantity' => 10,
            'sku' => 'LAP-SIL-001'
        ]);

        // Create order using factory for required fields
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => Order::STATUS_COMPLETED,
            'final_total' => 2000,
            'payment_status' => 'paid'
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'product_name' => 'Laptop',
            'variant_name' => 'Silver',
            'quantity' => 2,
            'price' => 1000
        ]);

        $variant->decrement('stock_quantity', 2); // Now 8

        $this->actingAs($user);

        // Submit partial return request (return 1 out of 2)
        $response = $this->post(route('account.orders.return_submit', $order->id), [
            'reason' => 'Defective',
            'note' => 'Scratched',
            'items' => [
                $orderItem->id => [
                    'selected' => '1',
                    'quantity' => '1'
                ]
            ]
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('order_return_requests', [
            'order_id' => $order->id,
            'refund_amount' => 1000,
            'status' => 'pending'
        ]);

        $returnRequest = OrderReturnRequest::where('order_id', $order->id)->first();
        $this->assertDatabaseHas('order_return_items', [
            'order_return_request_id' => $returnRequest->id,
            'order_item_id' => $orderItem->id,
            'quantity' => 1
        ]);
    }

    public function test_admin_completing_return_restores_correct_stock()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        
        $category = Category::create(['name' => 'IT', 'slug' => 'it', 'is_active' => true]);

        $product = Product::create(['name' => 'Phone', 'slug' => 'phone', 'price' => 500, 'is_active' => true, 'category_id' => $category->id]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'variant_name' => 'Black',
            'price' => 500,
            'stock_quantity' => 10,
            'sku' => 'PHN-BLK-001'
        ]);

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => Order::STATUS_COMPLETED,
            'final_total' => 1000,
            'payment_status' => 'paid'
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'product_name' => 'Phone',
            'variant_name' => 'Black',
            'quantity' => 2,
            'price' => 500
        ]);

        $variant->decrement('stock_quantity', 2); // Stock is 8

        // Create return request for 1 item
        $returnRequest = OrderReturnRequest::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'reason' => 'Changed mind',
            'refund_amount' => 500,
            'status' => 'approved' 
        ]);

        $returnItem = $returnRequest->items()->create([
            'order_item_id' => $orderItem->id,
            'quantity' => 1,
            'price' => 500
        ]);

        $this->actingAs($admin);

        // Complete the return
        $returnService = app(\App\Services\ReturnService::class);
        $returnService->complete($returnRequest, $admin);

        // Assertions
        $variant->refresh();
        $this->assertEquals(9, $variant->stock_quantity); // 8 + 1 = 9

        $order->refresh();
        $this->assertEquals(Order::STATUS_PARTIALLY_RETURNED, $order->status);
        $this->assertEquals('partially_refunded', $order->payment_status);
        
        $returnRequest->refresh();
        $this->assertEquals('completed', $returnRequest->status);
    }
}
