<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $environment = app()->environment();

        $this->command->info("Running seeders for environment: {$environment}");

        if ($environment === 'production') {
            // Use simpler, safer seeder for production
            $this->call([
                ProductionSeeder::class,
            ]);
        } else {
            // Use full data seeder for development/staging
            $this->call([
                AdminUserSeeder::class,
                RealDataSeeder::class,
            ]);
        }
    }
}
