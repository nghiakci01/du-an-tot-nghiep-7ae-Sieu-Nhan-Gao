<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);
    }

    private function makeOrder(string $status = 'pending'): Order
    {
        return Order::create([
            'user_id' => $this->customer->id,
            'total_price' => 500000,
            'final_total' => 500000,
            'status' => $status,
            'payment_method' => 'cod',
            'shipping_address' => '123 Đường Test, TP.HCM',
            'name' => 'Nguyen Van A',
            'phone' => '0901234567',
            'province' => 'TP.HCM',
            'address' => '123 Đường Test',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_view_orders_list()
    {
        $this->makeOrder();

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.index');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_view_single_order()
    {
        $order = $this->makeOrder();

        $response = $this->actingAs($this->admin)->get(route('admin.orders.show', $order));

        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.show');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_filter_orders_by_status()
    {
        $this->makeOrder('pending');
        $this->makeOrder('completed');

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index', ['status' => 'pending']));

        $response->assertStatus(200);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_update_order_status()
    {
        $order = $this->makeOrder('pending');

        // Mock OrderService để tránh phụ thuộc OrderHistory, email, stock
        $this->mock(OrderService::class, function ($mock) use ($order) {
            $mock->shouldReceive('updateOrderStatus')
                ->once()
                ->with(\Mockery::on(fn ($o) => $o->id === $order->id), 'confirmed', \Mockery::any())
                ->andReturnNull();
        });

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'status' => 'confirmed',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function order_status_is_required_on_update()
    {
        $order = $this->makeOrder('pending');

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'status' => '',
        ]);

        $response->assertSessionHasErrors(['status']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_delete_cancelled_order()
    {
        $order = $this->makeOrder('cancelled');

        $response = $this->actingAs($this->admin)->delete(route('admin.orders.destroy', $order));

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_cannot_delete_pending_order()
    {
        $order = $this->makeOrder('pending');

        $response = $this->actingAs($this->admin)->delete(route('admin.orders.destroy', $order));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_print_order()
    {
        $order = $this->makeOrder();

        $response = $this->actingAs($this->admin)->get(route('admin.orders.print', $order));

        $response->assertStatus(200);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_cannot_update_status_of_unpaid_online_order()
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'total_price' => 500000,
            'final_total' => 500000,
            'status' => 'pending',
            'payment_method' => 'VNPAY',
            'payment_status' => 'pending',
            'shipping_address' => '123 Đường Test, TP.HCM',
            'name' => 'Nguyen Van A',
            'phone' => '0901234567',
            'province' => 'TP.HCM',
            'address' => '123 Đường Test',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'status' => 'confirmed',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals('pending', $order->fresh()->status);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function guest_cannot_access_orders()
    {
        $response = $this->get(route('admin.orders.index'));

        // AdminMiddleware redirect về '/' hoặc login
        $response->assertStatus(302);
    }
}
