<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

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
        $response = $this->actingAs($this->admin)->get('/admin/categories');
        $response->assertStatus(200);
        $response->assertSee('Cat 1');
        $response->assertSee('Cat 2');
    }

    public function test_admin_can_create_category()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }

    public function test_admin_can_update_category()
    {
        $category = Category::create(['name' => 'Old Name', 'slug' => 'old-name']);
        
        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name' => 'New Name',
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'New Name',
            'slug' => 'new-name'
        ]);
    }

    public function test_admin_cannot_set_parent_if_category_has_children()
    {
        $parent = Category::create(['name' => 'Parent', 'slug' => 'parent']);
        $child = Category::create(['name' => 'Child', 'slug' => 'child', 'parent_id' => $parent->id]);
        $other = Category::create(['name' => 'Other', 'slug' => 'other']);

        // Thử biến $parent thành con của $other
        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $parent), [
            'name' => 'Parent Updated',
            'parent_id' => $other->id
        ]);

        $response->assertSessionHas('error', 'Không thể chuyển danh mục có danh mục con thành danh mục con.');
        $this->assertEquals(null, $parent->fresh()->parent_id);
    }

    public function test_admin_can_delete_empty_category()
    {
        $category = Category::create(['name' => 'To Delete', 'slug' => 'to-delete']);
        
        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));
        
        $response->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_can_delete_category_recursively()
    {
        $parent = Category::create(['name' => 'Parent', 'slug' => 'parent']);
        $child = Category::create(['name' => 'Child', 'slug' => 'child', 'parent_id' => $parent->id]);
        $grandchild = Category::create(['name' => 'Grand Child', 'slug' => 'grand-child', 'parent_id' => $child->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $parent));

        $response->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $parent->id]);
        $this->assertDatabaseMissing('categories', ['id' => $child->id]);
        $this->assertDatabaseMissing('categories', ['id' => $grandchild->id]);
    }

    public function test_admin_cannot_delete_category_with_active_products()
    {
        $category = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
        
        // Tạo sản phẩm (giả định có migration và factory cơ bản hoặc tạo trực tiếp)
        // Lưu ý: Có thể cần mock hoặc tạo đầy đủ các trường bắt buộc của Product
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'iPhone',
            'slug' => 'iphone',
            'price' => 1000,
            'is_active' => true
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertSessionHas('error', 'Không thể xóa danh mục vì vẫn còn sản phẩm đang hoạt động bên trong.');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_admin_can_delete_category_by_force_deleting_trashed_products()
    {
        $category = Category::create(['name' => 'Old Stuff', 'slug' => 'old-stuff']);
        
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Broken Phone',
            'slug' => 'broken-phone',
            'price' => 10,
            'is_active' => false
        ]);
        
        $product->delete(); // Soft delete

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseMissing('products', ['id' => $product->id]); // Đã bị force delete
    }
}
