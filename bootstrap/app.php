<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            // EMERGENCY DISABLE ALL HTTPS MIDDLEWARE - Causing Health:Severe
            // 'force.https' => \App\Http\Middleware\SafeForceHttps::class,
            // 'force.https.assets' => \App\Http\Middleware\ForceHttpsAssets::class,
        ]);

        // EMERGENCY DISABLE ALL HTTPS FORCING - Health:Severe
        // Apply HTTPS for assets only (không redirect)
        // if (app()->environment('production')) {
        //     $middleware->web(prepend: [
        //         \App\Http\Middleware\ForceHttpsAssets::class,
        //     ]);
        // }

        // EMERGENCY DISABLE - HTTPS redirect causing Health:Severe
        // Apply Safe HTTPS redirect globally in production
        // if (app()->environment('production')) {
        //     $middleware->web(prepend: [
        //         \App\Http\Middleware\SafeForceHttps::class,
        //     ]);
        // }
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
