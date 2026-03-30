<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'category_id' => Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'short_description' => $this->faker->paragraph(),
            'description' => $this->faker->text(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'sale_price' => null,
            'is_active' => true,
            'is_featured' => $this->faker->boolean(20),
            'image' => null,
        ];
    }
}
