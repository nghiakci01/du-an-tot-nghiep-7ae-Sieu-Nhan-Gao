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
        Schema::table('coupon_user', function (Blueprint $table) {
            $table->timestamp('used_at')->nullable()->after('claimed_at');
            $table->foreignId('order_id')->nullable()->after('used_at')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupon_user', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn(['used_at', 'order_id']);
        });
    }
};
