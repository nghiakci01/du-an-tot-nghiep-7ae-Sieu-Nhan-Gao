<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }

    private function makeCustomer(): User
    {
        return User::create([
            'name' => 'Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_view_dashboard()
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function guest_cannot_access_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function customer_cannot_access_admin_dashboard()
    {
        $customer = $this->makeCustomer();

        $response = $this->actingAs($customer)->get(route('admin.dashboard'));

        // AdminMiddleware redirect về '/' khi không có quyền
        $response->assertRedirect('/');
    }
}
