<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class AdminBrandTest extends TestCase {
    use RefreshDatabase;
    public function test_admin_brand_management() {
        $response = $this->get('/admin/brands');
        $this->assertContains($response->status(), [302]);
    }
}
