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
        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
        if ($driver === 'sqlite') {
            // For SQLite, drop and recreate the column with new enum
            \Illuminate\Support\Facades\DB::statement("
                ALTER TABLE order_return_requests ADD COLUMN status_new TEXT CHECK (status_new IN ('pending', 'approved', 'rejected', 'shipping_back', 'received', 'refunded', 'exchanged')) DEFAULT 'pending';
                UPDATE order_return_requests SET status_new = status;
                ALTER TABLE order_return_requests DROP COLUMN status;
                ALTER TABLE order_return_requests RENAME COLUMN status_new TO status;
            ");
        } else {
            // For MySQL/PostgreSQL
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE order_return_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'shipping_back', 'received', 'refunded', 'exchanged') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
        if ($driver === 'sqlite') {
            \Illuminate\Support\Facades\DB::statement("
                ALTER TABLE order_return_requests ADD COLUMN status_old TEXT CHECK (status_old IN ('pending', 'approved', 'completed', 'rejected')) DEFAULT 'pending';
                UPDATE order_return_requests SET status_old = status;
                ALTER TABLE order_return_requests DROP COLUMN status;
                ALTER TABLE order_return_requests RENAME COLUMN status_old TO status;
            ");
        } else {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE order_return_requests MODIFY COLUMN status ENUM('pending', 'approved', 'completed', 'rejected') DEFAULT 'pending'");
        }
    }
};
