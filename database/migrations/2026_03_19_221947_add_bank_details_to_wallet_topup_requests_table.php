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
        Schema::table('wallet_topup_requests', function (Blueprint $table) {
            $table->foreignId('bank_setting_id')->nullable()->after('user_id')->constrained('bank_settings')->nullOnDelete();
            $table->string('dest_bank_name')->nullable()->after('amount');
            $table->string('dest_account_number')->nullable()->after('dest_bank_name');
            $table->string('dest_account_name')->nullable()->after('dest_account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_topup_requests', function (Blueprint $table) {
            $table->dropForeign(['bank_setting_id']);
            $table->dropColumn(['bank_setting_id', 'dest_bank_name', 'dest_account_number', 'dest_account_name']);
        });
    }
};
