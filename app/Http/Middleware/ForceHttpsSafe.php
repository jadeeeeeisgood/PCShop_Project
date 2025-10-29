<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsSafe
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip HTTPS redirect on AWS ELB health checks
        if ($request->header('User-Agent') === 'ELB-HealthChecker/2.0') {
            return $next($request);
        }

        // Skip if running on localhost or testing
        if (app()->environment(['local', 'testing'])) {
            return $next($request);
        }

        // Only force HTTPS in production and if not already secure
        if (app()->environment('production') && !$request->secure()) {
            // Check if we're behind a proxy (like AWS ELB)
            $forwarded = $request->header('HTTP_X_FORWARDED_PROTO');
            if ($forwarded !== 'https') {
                return redirect()->secure($request->getRequestUri(), 301);
            }
        }

        return $next($request);
    }
}