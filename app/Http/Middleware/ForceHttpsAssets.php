<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsAssets
{
    /**
     * Handle an incoming request - Force HTTPS for assets only
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip if not in production
        if (!app()->environment('production')) {
            return $next($request);
        }

        // Skip health checks
        if ($request->header('User-Agent') === 'ELB-HealthChecker/2.0') {
            return $next($request);
        }

        // Force HTTPS for asset URLs only (không redirect requests)
        if ($request->isSecure() || $request->header('HTTP_X_FORWARDED_PROTO') === 'https') {
            URL::forceScheme('https');
            URL::forceRootUrl('https://www.pcshopvn.id.vn');
        }

        return $next($request);
    }
}