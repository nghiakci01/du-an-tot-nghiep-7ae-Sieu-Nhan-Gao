<?php

namespace Tests\Feature\Admin;

use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariantTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create initial attributes
        Size::create(['name' => 'S', 'display_order' => 1, 'is_active' => true]);
        Size::create(['name' => 'M', 'display_order' => 2, 'is_active' => true]);
        Color::create(['name' => 'Red', 'hex_code' => '#FF0000', 'display_order' => 1, 'is_active' => true]);
        Color::create(['name' => 'Blue', 'hex_code' => '#0000FF', 'display_order' => 2, 'is_active' => true]);
    }

    public function test_can_create_product_with_variants()
    {
        $category = \App\Models\Category::create(['name' => 'Test Category', 'slug' => 'test-category']);

        $size = Size::first();
        $color = Color::first();

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'New Product',
            'slug' => 'new-product',
            'category_id' => $category->id,
            'price' => 100,
            'variants' => [
                [
                    'size_id' => $size->id,
                    'color_id' => $color->id,
                    'price' => 110,
                    'stock_quantity' => 10,
                    'sku' => 'NP-S-RED',
                ],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('product_variants', [
            'size_id' => $size->id,
            'color_id' => $color->id,
            'size' => 'S',
            'color' => 'Red',
            'sku' => 'NP-S-RED',
        ]);
    }

    public function test_cannot_create_product_with_duplicate_variants()
    {
        $category = \App\Models\Category::create(['name' => 'Test Category', 'slug' => 'test-category']);
        $size = Size::first();
        $color = Color::first();

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'Duplicate Product',
            'category_id' => $category->id,
            'price' => 100,
            'variants' => [
                ['size_id' => $size->id, 'color_id' => $color->id, 'stock_quantity' => 10],
                ['size_id' => $size->id, 'color_id' => $color->id, 'stock_quantity' => 10], // Duplicate
            ],
        ]);

        $response->assertSessionHasErrors(['variants']);
    }

    public function test_sale_price_must_be_less_than_regular_price()
    {
        $category = \App\Models\Category::create(['name' => 'Test Category', 'slug' => 'test-category']);
        $size = Size::first();
        $color = Color::first();

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'Price Test',
            'category_id' => $category->id,
            'price' => 100,
            'variants' => [
                [
                    'size_id' => $size->id,
                    'color_id' => $color->id,
                    'price' => 100,
                    'sale_price' => 120, // Invalid: sale > regular
                    'stock_quantity' => 10,
                ],
            ],
        ]);

        $response->assertSessionHasErrors(['variants.0.sale_price']);
    }

    public function test_can_update_variants_on_existing_product()
    {
        $category = \App\Models\Category::create(['name' => 'Test Category', 'slug' => 'test-category']);
        $product = Product::create([
            'name' => 'Original Product',
            'slug' => 'original-product',
            'category_id' => $category->id,
            'price' => 100,
        ]);

        $size = Size::first();
        $color = Color::first();

        $variant = $product->variants()->create([
            'size_id' => $size->id,
            'color_id' => $color->id,
            'size' => 'S',
            'color' => 'Red',
            'stock_quantity' => 5,
            'sku' => 'ORIG-RED',
        ]);

        $newColor = Color::where('name', 'Blue')->first();

        $response = $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
            'name' => 'Updated Product',
            'slug' => 'original-product',
            'category_id' => $category->id,
            'price' => 100,
            'variants' => [
                [
                    'id' => $variant->id,
                    'size_id' => $size->id,
                    'color_id' => $newColor->id, // Change color
                    'stock_quantity' => 20,
                ],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'color_id' => $newColor->id,
            'color' => 'Blue',
            'stock_quantity' => 20,
        ]);
    }
}
