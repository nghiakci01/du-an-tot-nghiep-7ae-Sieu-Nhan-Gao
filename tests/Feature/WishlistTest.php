<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class WishlistTest extends TestCase {
    use RefreshDatabase;
    public function test_wishlist_access() {
        $response = $this->get('/wishlist');
        $this->assertContains($response->status(), [200, 302]);
    }
}
