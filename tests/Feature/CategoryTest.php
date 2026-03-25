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
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_categories()
    {
        Category::create(['name' => 'Cat 1', 'slug' => 'cat-1']);
        Category::create(['name' => 'Cat 2', 'slug' => 'cat-2']);
        Category::create(['name' => 'Cat 3', 'slug' => 'cat-3']);
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
