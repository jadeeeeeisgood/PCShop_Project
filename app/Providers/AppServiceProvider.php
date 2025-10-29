<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Force HTTPS URLs in production (Safe version for AWS ELB)
        if (app()->environment('production')) {
            // Only force scheme if not behind proxy
            $request = request();
            if ($request && !$request->header('HTTP_X_FORWARDED_PROTO')) {
                URL::forceScheme('https');
            }
            URL::forceRootUrl(config('app.url'));
        }

        // Set timezone
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
}
