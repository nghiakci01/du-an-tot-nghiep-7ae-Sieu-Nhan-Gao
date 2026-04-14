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
            $table->enum('type', ['refund', 'exchange'])->default('refund')->after('order_id');
            $table->enum('reason_type', ['wrong_size', 'disliked', 'defective', 'other'])->default('other')->after('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_return_requests', function (Blueprint $table) {
            $table->dropColumn(['type', 'reason_type']);
        });
    }
};
