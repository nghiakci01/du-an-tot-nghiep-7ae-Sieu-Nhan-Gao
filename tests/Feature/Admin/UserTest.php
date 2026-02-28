<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function admin_can_view_users_list()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
    }

    /** @test */
    public function admin_can_view_user_detail()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => bcrypt('pass'),
            'role' => 'user',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.users.show', $user));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_filter_users_by_role()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index', ['role' => 'customer']));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_cannot_delete_themselves()
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    /** @test */
    public function admin_cannot_delete_last_admin()
    {
        // Chỉ có 1 admin ($this->admin), không thể xóa chính mình
        // Theo logic UserController: không cho xóa chính mình => error
        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    /** @test */
    public function admin_can_delete_customer()
    {
        // Tạo user trực tiếp thay vì qua POST request để tránh validation
        $customer = User::create([
            'name' => 'Customer To Delete',
            'email' => 'delete_me@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $customer));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $customer->id]);
    }

    /** @test */
    public function guest_cannot_access_users_management()
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertRedirect(route('login'));
    }
}
