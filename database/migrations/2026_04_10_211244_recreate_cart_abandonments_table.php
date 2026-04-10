<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cart_abandonments')) {
            Schema::create('cart_abandonments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('session_id')->nullable()->index();
                $table->json('cart_data');
                $table->decimal('cart_total', 12, 2)->default(0);
                $table->integer('item_count')->default(0);
                $table->enum('status', ['abandoned', 'recovered', 'converted'])->default('abandoned');
                $table->timestamp('abandoned_at');
                $table->timestamp('recovered_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_abandonments');
    }
};
