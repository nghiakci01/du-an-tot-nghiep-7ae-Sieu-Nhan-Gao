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
        Schema::table('order_return_requests', function (Blueprint $table) {
            $table->text('shipping_proof')->nullable()->change();
            $table->text('video_proof')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_return_requests', function (Blueprint $table) {
            $table->string('shipping_proof')->nullable()->change();
            $table->string('video_proof')->nullable()->change();
        });
    }
};
