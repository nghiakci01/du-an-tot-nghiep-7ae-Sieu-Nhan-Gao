<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Drop foreign key and column from products table
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'brand_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropConstrainedForeignId('brand_id');
            });
        }

        // Drop multiple unused tables
        $tablesToDrop = [
            'brands',
            'suppliers',
            'warehouses',
            'inventory_vouchers',
            'inventory_voucher_details',
            'warehouse_stocks',
            'cart_abandonments',
            'reward_points',
            'reward_point_histories',
            'customer_tiers',
            'promotions',
            'loyalty_points'
        ];

        foreach ($tablesToDrop as $tableName) {
            Schema::dropIfExists($tableName);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not implemented for this cleanup migration
    }
};
