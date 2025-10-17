<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateFeaturedProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update first 3 products as featured
        Product::take(3)->update(['is_featured' => true]);

        echo "Updated first 3 products as featured\n";
    }
}