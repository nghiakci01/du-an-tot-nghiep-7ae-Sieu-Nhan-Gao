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

    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function admin_can_view_create_banner_form()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.banners.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.banners.create');
    }

    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function creating_banner_without_image_fails()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.banners.store'), [
            'title' => 'Banner Thiếu Ảnh',
            'position' => 'slider',
        ]);

        $response->assertSessionHasErrors(['image']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function banner_upload_with_invalid_file_does_not_trigger_500_error()
    {
        // Giả lập một file UploadedFile nhưng bị lỗi (ví dụ vượt quá kích thước PHP cho phép)
        // Khi đó isValid() sẽ trả về false và getRealPath() có thể là false/empty
        $file = new UploadedFile(
            path: '',
            originalName: 'large_image.jpg',
            mimeType: 'image/jpeg',
            error: UPLOAD_ERR_INI_SIZE,
            test: true
        );

        $response = $this->actingAs($this->admin)->post(route('admin.banners.store'), [
            'title' => 'Large Image',
            'image' => $file,
            'position' => 'slider',
        ]);

        // Thay vì lỗi 500 (ValueError), nó nên trả về lỗi validation bình thường
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['image']);
    }
}
