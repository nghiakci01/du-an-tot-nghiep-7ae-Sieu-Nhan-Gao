<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class AdminPostTest extends TestCase {
    use RefreshDatabase;
    public function test_admin_post_management() {
        $response = $this->get('/admin/posts');
        $this->assertContains($response->status(), [302]);
    }
}
