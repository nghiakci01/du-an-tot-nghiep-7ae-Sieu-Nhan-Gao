<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class AdminSettingTest extends TestCase {
    use RefreshDatabase;
    public function test_admin_setting_management() {
        $response = $this->get('/admin/system-settings');
        $this->assertContains($response->status(), [302]);
    }
}
