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
        // MySQL doesn't support direct enum modification easily with Schema::table and change() 
        // without Doctrine DBAL or using raw SQL. Let's use raw SQL for MySQL enum.
        if (\Illuminate\Support\Facades\DB::connection()->getDriverName() !== 'sqlite') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE order_return_requests MODIFY COLUMN status ENUM('pending', 'approved', 'shipping', 'received', 'completed', 'rejected') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (\Illuminate\Support\Facades\DB::connection()->getDriverName() !== 'sqlite') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE order_return_requests MODIFY COLUMN status ENUM('pending', 'approved', 'completed', 'rejected') DEFAULT 'pending'");
        }
    }
};
