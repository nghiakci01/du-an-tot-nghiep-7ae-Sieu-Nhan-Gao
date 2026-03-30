<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class SearchTest extends TestCase {
    use RefreshDatabase;
    public function test_search_functionality() {
        $response = $this->get('/search?q=test');
        $this->assertEquals(200, $response->status());
    }
}
