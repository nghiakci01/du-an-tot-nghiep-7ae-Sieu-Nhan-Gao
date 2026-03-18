<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class AdminDashboardTest extends TestCase {
    use RefreshDatabase;
    public function test_admin_dashboard_is_protected() {
        $response = $this->get('/admin');
        $this->assertContains($response->status(), [302]);
    }
}
