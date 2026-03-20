<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Giả lập user admin
        $this->admin = User::factory()->create(['role' => 'ADMIN']);
    }

    public function test_admin_can_view_categories()
    {
        Category::factory()->count(3)->create();
        $response = $this->actingAs($this->admin)->get('/admin/categories');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_category()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }
}
