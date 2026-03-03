<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\AuditLog;
use App\Models\Product;
use App\Models\User;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_product_triggers_audit_log()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $category = \App\Models\Category::create(['name' => 'Category', 'slug' => 'cat']);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 100,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'event' => 'created',
            'auditable_type' => Product::class,
            'auditable_id' => $product->id,
            'user_id' => $admin->id,
        ]);
    }

    public function test_updating_product_triggers_audit_log()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $category = \App\Models\Category::create(['name' => 'Category', 'slug' => 'cat']);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Old Name',
            'slug' => 'old-slug',
            'price' => 100,
        ]);

        $product->update(['name' => 'New Name']);

        $this->assertDatabaseHas('audit_logs', [
            'event' => 'updated',
            'auditable_type' => Product::class,
            'auditable_id' => $product->id,
        ]);

        $log = AuditLog::where('event', 'updated')->first();
        $this->assertEquals('Old Name', $log->old_values['name']);
        $this->assertEquals('New Name', $log->new_values['name']);
    }
}
