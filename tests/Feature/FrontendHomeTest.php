<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;

class FrontendHomeTest extends TestCase {
    use RefreshDatabase;

    public function test_frontend_home_page() {
        // Seed minimal data
        Category::create(['name' => 'Test', 'slug' => 'test']);
        Product::factory(3)->create();

        $response = $this->get('/');
        $this->assertIn($response->status(), [200, 302]);
    }
}
