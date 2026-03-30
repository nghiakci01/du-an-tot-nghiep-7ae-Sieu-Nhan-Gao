<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_products_index()
    {
        Product::factory()->count(3)->create();
        
        $response = $this->actingAs($this->admin)->get(route('admin.products.index'));
        
        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    public function test_admin_can_create_product_with_variants()
    {
        Storage::fake('public');
        $category = Category::factory()->create();
        $size = Size::factory()->create(['name' => 'L']);
        $color = Color::factory()->create(['name' => 'Red']);

        $data = [
            'category_id' => $category->id,
            'name' => 'New Awesome Product',
            'price' => 100,
            'is_active' => 1,
            'image' => UploadedFile::fake()->image('product.jpg', 400, 400),
            'variants' => [
                [
                    'size_id' => $size->id,
                    'color_id' => $color->id,
                    'price' => 110,
                    'stock_quantity' => 50,
                    'sku' => 'SKU-UNIQUE-001'
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $data);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'New Awesome Product']);
        $this->assertDatabaseHas('product_variants', ['sku' => 'SKU-UNIQUE-001', 'price' => '110.00']);
    }

    public function test_admin_can_update_product()
    {
        $product = Product::factory()->create(['name' => 'Old Product']);
        $size = Size::factory()->create();
        $color = Color::factory()->create();
        
        $response = $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
            'name' => 'Updated Product Name',
            'category_id' => $product->category_id,
            'price' => 200,
            'variants' => [
                [
                    'size_id' => $size->id,
                    'color_id' => $color->id,
                    'price' => 220,
                    'stock_quantity' => 10,
                    'sku' => 'SKU-UPDATED'
                ]
            ]
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name'
        ]);
    }

    public function test_admin_can_delete_product()
    {
        $product = Product::factory()->create();
        
        $response = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_admin_can_bulk_delete_products()
    {
        $products = Product::factory()->count(3)->create();
        $ids = $products->pluck('id')->toArray();

        // Thay đổi POST thành DELETE theo đúng route:list
        $response = $this->actingAs($this->admin)->delete(route('admin.products.bulk-delete'), [
            'ids' => $ids
        ]);

        $response->assertRedirect(route('admin.products.index'));
        foreach ($ids as $id) {
            $this->assertSoftDeleted('products', ['id' => $id]);
        }
    }
}
