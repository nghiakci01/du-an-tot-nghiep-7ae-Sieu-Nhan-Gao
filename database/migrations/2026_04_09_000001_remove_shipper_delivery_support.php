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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipper_id']);
            $table->dropColumn(['shipper_id', 'delivery_note']);
        });

        Schema::dropIfExists('order_delivery_proofs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('shipper_id')->nullable()->after('user_id');
            $table->text('delivery_note')->nullable()->after('note');

            $table->foreign('shipper_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('order_delivery_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->enum('file_type', ['image', 'video']);
            $table->timestamps();
        });
    }
};
