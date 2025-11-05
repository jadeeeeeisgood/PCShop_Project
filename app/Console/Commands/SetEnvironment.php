<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:set {environment : The environment to set (local|production)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set environment configuration for local or production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $environment = $this->argument('environment');

        if (!in_array($environment, ['local', 'production'])) {
            $this->error('Environment must be either "local" or "production"');
            return 1;
        }

        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            $this->error('.env file not found');
            return 1;
        }

        $envContent = File::get($envPath);

        if ($environment === 'local') {
            $this->info('Setting up for LOCAL environment...');

            // Update environment settings
            $envContent = preg_replace('/APP_ENV=.*/m', 'APP_ENV=local', $envContent);
            $envContent = preg_replace('/APP_DEBUG=.*/m', 'APP_DEBUG=true', $envContent);
            $envContent = preg_replace('/APP_URL=.*/m', 'APP_URL=http://127.0.0.1:8000', $envContent);

            // Database settings for local MySQL
            $envContent = preg_replace('/DB_CONNECTION=.*/m', 'DB_CONNECTION=mysql', $envContent);
            $envContent = preg_replace('/DB_HOST=.*/m', 'DB_HOST=127.0.0.1', $envContent);
            $envContent = preg_replace('/DB_DATABASE=.*/m', 'DB_DATABASE=pcshop', $envContent);
            $envContent = preg_replace('/DB_USERNAME=.*/m', 'DB_USERNAME=root', $envContent);
            $envContent = preg_replace('/DB_PASSWORD=.*/m', 'DB_PASSWORD=', $envContent);

        } else { // production
            $this->info('Setting up for PRODUCTION environment...');

            // Update environment settings
            $envContent = preg_replace('/APP_ENV=.*/m', 'APP_ENV=production', $envContent);
            $envContent = preg_replace('/APP_DEBUG=.*/m', 'APP_DEBUG=false', $envContent);
            $envContent = preg_replace('/APP_URL=.*/m', 'APP_URL=https://pcshop-final.eba-gm3xqw32.us-east-1.elasticbeanstalk.com', $envContent);

            // Database settings for production MySQL RDS
            $envContent = preg_replace('/DB_CONNECTION=.*/m', 'DB_CONNECTION=mysql', $envContent);
            $this->warn('Note: You need to manually update RDS connection details in .env:');
            $this->warn('- DB_HOST: Your RDS endpoint from MySQL Workbench');
            $this->warn('- DB_DATABASE: Your database name');
            $this->warn('- DB_USERNAME: Your RDS username');
            $this->warn('- DB_PASSWORD: Your RDS password');
        }
        File::put($envPath, $envContent);

        $this->info("Environment configuration updated for: {$environment}");
        $this->info('Run "php artisan config:clear" to apply changes');

        return 0;
    }
}
