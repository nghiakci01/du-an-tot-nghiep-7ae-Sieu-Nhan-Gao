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
        Schema::table('vton_histories', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('vton_model_id');
            $table->string('result_image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vton_histories', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('result_image')->nullable(false)->change();
        });
    }
};
