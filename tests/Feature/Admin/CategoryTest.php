<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_view_categories_list()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_view_create_category_form()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.create'));

        $response->assertStatus(200);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_create_category()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Ao Thun Nam',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Ao Thun Nam',
            'slug' => 'ao-thun-nam',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function category_image_must_be_valid_image_type()
    {
        // Test rằng file không phải image bị reject
        $file = UploadedFile::fake()->create('script.txt', 10, 'text/plain');

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Valid Category',
            'image' => $file,
        ]);

        $response->assertSessionHasErrors(['image']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function category_name_is_required()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_update_category()
    {
        $category = Category::create([
            'name' => 'Old Name',
            'slug' => 'old-name',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name' => 'New Name',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'New Name',
            'slug' => 'new-name',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_delete_empty_category()
    {
        $category = Category::create([
            'name' => 'Empty Category',
            'slug' => 'empty-category',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_delete_category_with_empty_children()
    {
        $parent = Category::create(['name' => 'Parent', 'slug' => 'parent']);
        Category::create(['name' => 'Child', 'slug' => 'child', 'parent_id' => $parent->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $parent));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $parent->id]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function cannot_set_category_with_children_as_subcategory()
    {
        $parent = Category::create(['name' => 'Parent', 'slug' => 'parent']);
        $child = Category::create(['name' => 'Child', 'slug' => 'child', 'parent_id' => $parent->id]);
        $other = Category::create(['name' => 'Other', 'slug' => 'other']);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $parent), [
            'name' => 'Parent',
            'parent_id' => $other->id, // Parent đang có con, không được chuyển thành con
        ]);

        $response->assertSessionHas('error');
    }
}
