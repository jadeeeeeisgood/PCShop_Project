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
        Schema::table('categories', function (Blueprint $table) {
            $table->text('description')->nullable()->after('slug');
            $table->unsignedBigInteger('parent_id')->nullable()->after('description');
            $table->integer('sort_order')->default(0)->after('parent_id');
            $table->boolean('is_active')->default(true)->after('sort_order');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->string('meta_title')->nullable()->after('is_featured');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('image')->nullable()->after('meta_keywords');
            $table->string('icon')->nullable()->after('image');
            $table->string('color', 7)->nullable()->after('icon');

            // Foreign key constraint
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn([
                'description',
                'parent_id',
                'sort_order',
                'is_active',
                'is_featured',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'image',
                'icon',
                'color'
            ]);
        });
    }
};
