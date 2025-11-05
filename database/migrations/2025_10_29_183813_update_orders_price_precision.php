<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Thay đổi precision từ decimal(10, 2) thành decimal(15, 2)
            // Cho phép giá trị lên đến 9,999,999,999,999.99 VNĐ
            $table->decimal('total', 15, 2)->change();
            $table->decimal('subtotal', 15, 2)->change();
            $table->decimal('shipping_fee', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total', 10, 2)->change();
            $table->decimal('subtotal', 10, 2)->change();
            $table->decimal('shipping_fee', 10, 2)->change();
        });
    }
};
