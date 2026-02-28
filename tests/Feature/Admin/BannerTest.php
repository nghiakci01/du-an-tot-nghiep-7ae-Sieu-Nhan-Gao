<?php

namespace Tests\Feature\Admin;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BannerTest extends TestCase
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

    /** @test */
    public function admin_can_view_banners_list()
    {
        Banner::create([
            'title' => 'Test Banner',
            'image' => 'banners/test.jpg',
            'position' => 'slider',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.banners.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.banners.index');
    }

    /** @test */
    public function admin_can_view_create_banner_form()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.banners.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.banners.create');
    }

    /** @test */
    public function admin_can_create_banner_with_image()
    {
        // Dùng create() thay vì image() để không cần GD extension
        $file = UploadedFile::fake()->create('banner.jpg', 200, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post(route('admin.banners.store'), [
            'title' => 'Banner Khuyến Mãi',
            'image' => $file,
            'position' => 'slider',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.banners.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('banners', [
            'title' => 'Banner Khuyến Mãi',
            'position' => 'slider',
        ]);
    }

    /** @test */
    public function creating_banner_without_image_fails()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.banners.store'), [
            'title' => 'Banner Thiếu Ảnh',
            'position' => 'slider',
        ]);

        $response->assertSessionHasErrors(['image']);
    }

    /** @test */
    public function admin_can_edit_banner()
    {
        $banner = Banner::create([
            'title' => 'Old Title',
            'image' => 'banners/old.jpg',
            'position' => 'slider',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.banners.edit', $banner));

        $response->assertStatus(200);
        $response->assertViewIs('admin.banners.edit');
    }

    /** @test */
    public function admin_can_update_banner_without_new_image()
    {
        $banner = Banner::create([
            'title' => 'Old Title',
            'image' => 'banners/old.jpg',
            'position' => 'slider',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.banners.update', $banner), [
            'title' => 'New Title',
            'position' => 'slider',
            'is_active' => false,
        ]);

        $response->assertRedirect(route('admin.banners.index'));
        $this->assertDatabaseHas('banners', [
            'id' => $banner->id,
            'title' => 'New Title',
            'is_active' => false,
        ]);
    }

    /** @test */
    public function admin_can_update_banner_with_new_image()
    {
        $banner = Banner::create([
            'title' => 'Test Banner',
            'image' => 'banners/old.jpg',
            'position' => 'slider',
            'is_active' => true,
        ]);

        $newFile = UploadedFile::fake()->create('new_banner.png', 200, 'image/png');

        $response = $this->actingAs($this->admin)->put(route('admin.banners.update', $banner), [
            'title' => 'Updated Title',
            'image' => $newFile,
            'position' => 'slider',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.banners.index'));
        $response->assertSessionHas('success');
    }

    /** @test */
    public function admin_can_delete_banner()
    {
        $banner = Banner::create([
            'title' => 'Delete Me',
            'image' => 'banners/delete.jpg',
            'position' => 'slider',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.banners.destroy', $banner));

        $response->assertRedirect(route('admin.banners.index'));
        $this->assertDatabaseMissing('banners', ['id' => $banner->id]);
    }

    /** @test */
    public function banner_image_must_be_valid_format()
    {
        // File PDF sẽ fail validation (mimes:jpeg,png,jpg,gif)
        // Không cần GD, chỉ cần MIME type sai là đủ
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.banners.store'), [
            'title' => 'Invalid File',
            'image' => $file,
            'position' => 'slider',
        ]);

        $response->assertSessionHasErrors(['image']);
    }
}
