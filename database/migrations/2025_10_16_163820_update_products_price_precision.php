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
        Schema::table('products', function (Blueprint $table) {
            // Thay đổi precision từ decimal(10, 2) thành decimal(15, 2)
            // Cho phép giá trị lên đến 9,999,999,999,999.99 VNĐ
            $table->decimal('price', 15, 2)->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Cập nhật cả bảng order_items
            $table->decimal('price', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
        });
    }
};
