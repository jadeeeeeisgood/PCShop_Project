<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\ProductionSeeder;
use App\Models\Category;
use App\Models\Product;

class SeedProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:production {--force : Force seeding even if data exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed essential data for production environment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Production Seeding Command ===');

        // Check current state
        $categoryCount = Category::count();
        $productCount = Product::count();

        $this->info("Current state:");
        $this->info("Categories: {$categoryCount}");
        $this->info("Products: {$productCount}");

        if (($categoryCount > 0 || $productCount > 0) && !$this->option('force')) {
            if (!$this->confirm('Data already exists. Continue with seeding?')) {
                $this->info('Seeding cancelled.');
                return 0;
            }
        }

        try {
            $this->info('Running ProductionSeeder...');

            $seeder = new ProductionSeeder();
            $seeder->setCommand($this);
            $seeder->run();

            // Check results
            $newCategoryCount = Category::count();
            $newProductCount = Product::count();

            $this->info('=== Seeding Results ===');
            $this->info("Categories: {$categoryCount} → {$newCategoryCount}");
            $this->info("Products: {$productCount} → {$newProductCount}");

            if ($newProductCount > 0) {
                $this->info('✓ Production seeding completed successfully!');
                return 0;
            } else {
                $this->error('✗ No products were created!');
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('Production seeding failed: ' . $e->getMessage());
            return 1;
        }
    }
}