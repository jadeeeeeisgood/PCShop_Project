<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DebugDatabase extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'debug:database {--detailed : Show detailed information}';

    /**
     * The console command description.
     */
    protected $description = 'Debug database state and show counts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== DATABASE DEBUG INFORMATION ===');
        $this->newLine();

        // Environment info
        $this->info('Environment: ' . config('app.env'));
        $this->info('Database: ' . config('database.default'));
        $this->info('URL: ' . config('app.url'));
        $this->newLine();

        // Basic counts
        $this->info('=== RECORD COUNTS ===');
        $this->table(
            ['Model', 'Count'],
            [
                ['Users', User::count()],
                ['Categories', Category::count()],
                ['Products', Product::count()],
                ['Orders', Order::count()],
            ]
        );

        if ($this->option('detailed')) {
            $this->newLine();
            $this->info('=== DETAILED INFORMATION ===');

            // Categories
            if (Category::count() > 0) {
                $this->info('Categories:');
                $categories = Category::withCount('products')->get();
                $categoryData = [];
                foreach ($categories as $category) {
                    $categoryData[] = [
                        $category->id,
                        $category->name,
                        $category->products_count
                    ];
                }
                $this->table(['ID', 'Name', 'Products Count'], $categoryData);
            }

            // Sample products
            if (Product::count() > 0) {
                $this->info('Sample Products (first 5):');
                $products = Product::with('category')->take(5)->get();
                $productData = [];
                foreach ($products as $product) {
                    $productData[] = [
                        $product->id,
                        substr($product->name, 0, 30),
                        $product->category->name ?? 'No Category',
                        number_format((float) $product->price) . ' VND'
                    ];
                }
                $this->table(['ID', 'Name', 'Category', 'Price'], $productData);
            }

            // Database tables info
            $this->newLine();
            $this->info('=== DATABASE TABLES ===');
            try {
                $tables = DB::select('SHOW TABLES');
                $tableNames = [];
                foreach ($tables as $table) {
                    $tableName = array_values((array) $table)[0];
                    $count = DB::table($tableName)->count();
                    $tableNames[] = [$tableName, $count];
                }
                $this->table(['Table', 'Records'], $tableNames);
            } catch (\Exception $e) {
                $this->error('Could not retrieve table information: ' . $e->getMessage());
            }
        }

        // Recommendations
        $this->newLine();
        $this->info('=== RECOMMENDATIONS ===');

        if (Category::count() == 0) {
            $this->warn('⚠️  No categories found. Run: php artisan db:seed --class=ProductionSeeder');
        }

        if (Product::count() == 0) {
            $this->warn('⚠️  No products found. Run: php artisan db:seed --class=ProductionSeeder');
        }

        if (Category::count() > 0 && Product::count() == 0) {
            $this->warn('⚠️  Categories exist but no products. Check ProductionSeeder.');
        }

        if (Category::count() > 0 && Product::count() > 0) {
            $this->info('✅ Database looks healthy!');
        }

        $this->newLine();
        $this->info('Debug completed.');
    }
}