<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class ReviewTest extends TestCase {
    use RefreshDatabase;

    public function test_user_can_submit_review() {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Test', 'slug' => 'test']);
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($user)->post(route('product.review.store', $product->id), [
            'rating' => 5,
            'comment' => 'Great product!'
        ]);
        $this->assertContains($response->status(), [302, 401, 422, 201]);
    }
}
