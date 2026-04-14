<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use DB statement to modify enum as Schema builder has limitations with enums
        if (\Illuminate\Support\Facades\DB::connection()->getDriverName() !== 'sqlite') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'shipped', 'completed', 'cancelled', 'failed', 'returned') NOT NULL DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum
        if (\Illuminate\Support\Facades\DB::connection()->getDriverName() !== 'sqlite') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'shipped', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
        }
    }
};
