<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsAssetsOnly
{
    /**
     * Force HTTPS for generated URLs only - NO REQUEST REDIRECTS
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

        // IMPORTANT: Only force URL scheme, NEVER redirect requests
        // This ensures CSS/JS/Images use HTTPS when page is accessed via HTTPS
        if (
            $request->isSecure() ||
            $request->header('HTTP_X_FORWARDED_PROTO') === 'https' ||
            $request->getHost() === 'www.pcshopvn.id.vn'
        ) {

            // Force all generated URLs to use HTTPS
            URL::forceScheme('https');

            // Set the correct root URL based on the domain
            if ($request->getHost() === 'www.pcshopvn.id.vn') {
                URL::forceRootUrl('https://www.pcshopvn.id.vn');
            }
        }

        // Continue with the request - NO REDIRECTS!
        return $next($request);
    }
}