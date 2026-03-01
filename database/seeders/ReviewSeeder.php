<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $users = User::all();

        if ($products->isEmpty() || $users->isEmpty()) {
            return;
        }

        foreach ($products as $product) {
            // Randomly give 2-5 reviews per product
            $numReviews = rand(2, 5);
            for ($i = 0; $i < $numReviews; $i++) {
                Review::create([
                    'product_id' => $product->id,
                    'user_id' => $users->random()->id,
                    'rating' => rand(3, 5),
                    'comment' => 'Sản phẩm chất lượng thực tế giống hình. Shop phục vụ nhiệt tình.',
                    'is_active' => true,
                ]);
            }
        }
    }
}
