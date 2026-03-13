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
        Schema::create('vton_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image'); // Path to mannequin photo
            $table->enum('gender', ['male', 'female', 'kid'])->default('female');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vton_models');
    }
};
