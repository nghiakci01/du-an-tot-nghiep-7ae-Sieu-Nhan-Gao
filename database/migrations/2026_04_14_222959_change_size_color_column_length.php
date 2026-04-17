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
        Schema::table('sizes', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
        Schema::table('sizes', function (Blueprint $table) {
            $table->string('name', 25)->unique()->change();
        });

        Schema::table('colors', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
        Schema::table('colors', function (Blueprint $table) {
            $table->string('name', 25)->unique()->change();
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('size', 25)->nullable()->change();
            $table->string('color', 25)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
        Schema::table('sizes', function (Blueprint $table) {
            $table->string('name', 255)->unique()->change();
        });

        Schema::table('colors', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
        Schema::table('colors', function (Blueprint $table) {
            $table->string('name', 255)->unique()->change();
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('size', 50)->nullable()->change();
            $table->string('color', 50)->nullable()->change();
        });
    }
};
