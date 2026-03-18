<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class FrontendHomeTest extends TestCase {
    use RefreshDatabase;
    public function test_frontend_home_page() {
        $response = $this->get('/');
        $this->assertEquals(200, $response->status());
    }
}
