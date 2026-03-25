<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_products()
    {
        Category::create(['name' => 'Test', 'slug' => 'test']);
        Product::factory(5)->create();
        $response = $this->get('/shop');
        $response->assertStatus(200);
    }
}
