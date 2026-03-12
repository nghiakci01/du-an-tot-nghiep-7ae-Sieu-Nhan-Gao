<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            // Localized Lookups
            SettingSeeder::class,
            ChatbotSettingSeeder::class,
            
            // Core Entities (Lookups first)
            UserSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ColorSeeder::class,
            SizeSeeder::class,
            SupplierSeeder::class,
            WarehouseSeeder::class,
            
            // Content & Products (Depends on above)
            PostCategorySeeder::class,
            PostSeeder::class,
            ProductSeeder::class,
            
            // Interaction & Marketing (Depends on products/users)
            ReviewSeeder::class,
            CouponSeeder::class,
            BannerSeeder::class,
        ]);
    }
}
