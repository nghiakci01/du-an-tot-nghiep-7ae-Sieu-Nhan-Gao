<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class AdminUserTest extends TestCase {
    use RefreshDatabase;
    public function test_admin_user_management() {
        $response = $this->get('/admin/users');
        $this->assertContains($response->status(), [302]);
    }
}
