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
            $table->json('images')->nullable()->after('image');
            $table->json('specifications')->nullable()->after('images');
            $table->boolean('is_featured')->default(false)->after('specifications');
            $table->integer('views')->default(0)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['images', 'specifications', 'is_featured', 'views']);
        });
    }
};
