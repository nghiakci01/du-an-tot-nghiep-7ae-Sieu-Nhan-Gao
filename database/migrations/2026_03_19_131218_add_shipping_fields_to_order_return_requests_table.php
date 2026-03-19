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
            $table->text('shipping_info')->nullable()->after('reason')->comment('Thông tin vận chuyển khách hàng gửi (Mã vận đơn, đơn vị vận chuyển)');
            $table->string('shipping_proof')->nullable()->after('shipping_info')->comment('Ảnh minh chứng đã gửi hàng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_return_requests', function (Blueprint $table) {
            $table->dropColumn(['shipping_info', 'shipping_proof']);
        });
    }
};
