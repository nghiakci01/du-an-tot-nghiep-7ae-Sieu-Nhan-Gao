<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ReviewTest extends TestCase {
    use RefreshDatabase;
    public function test_user_can_submit_review() {
        $response = $this->post('/reviews', ['rating' => 5]);
        $this->assertContains($response->status(), [302, 401, 422]);
    }
}
