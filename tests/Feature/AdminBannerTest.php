<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class AdminBannerTest extends TestCase {
    use RefreshDatabase;
    public function test_admin_banner_management() {
        $response = $this->get('/admin/banners');
        $this->assertContains($response->status(), [302]);
    }
}
