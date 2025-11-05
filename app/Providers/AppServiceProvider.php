<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS for assets only in production (AWS ELB safe)
        if ($this->app->environment('production')) {
            // Force all generated URLs to use HTTPS
            URL::forceScheme('https');

            // Configure clean URL generation
            URL::forceRootUrl('https://www.pcshopvn.id.vn');
        }

        // Set timezone
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
}
