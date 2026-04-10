<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE cart_abandonments MODIFY COLUMN status ENUM('abandoned', 'recovered', 'converted', 'pending_notification', 'notified') DEFAULT 'abandoned'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE cart_abandonments MODIFY COLUMN status ENUM('abandoned', 'recovered', 'converted') DEFAULT 'abandoned'");
    }
};
