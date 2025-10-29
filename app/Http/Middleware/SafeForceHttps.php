<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SafeForceHttps
{
    /**
     * Handle an incoming request - AWS ELB Compatible version
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip HTTPS redirect on AWS ELB health checks
        if ($request->header('User-Agent') === 'ELB-HealthChecker/2.0') {
            return $next($request);
        }

        // Skip if running on localhost, testing, or staging
        if (app()->environment(['local', 'testing', 'staging'])) {
            return $next($request);
        }

        // Skip for specific routes that should always work over HTTP
        $skipRoutes = ['/up', '/health', '/debug.php'];
        if (in_array($request->getPathInfo(), $skipRoutes)) {
            return $next($request);
        }

        // Only apply in production environment
        if (app()->environment('production')) {

            // Method 1: Check if we're behind AWS ELB (Load Balancer)
            $forwardedProto = $request->header('HTTP_X_FORWARDED_PROTO');
            $forwardedPort = $request->header('HTTP_X_FORWARDED_PORT');

            // Method 2: Check direct HTTPS
            $isSecure = $request->secure();

            // Method 3: Check if behind proxy and using HTTPS
            $isBehindHttpsProxy = ($forwardedProto === 'https') || ($forwardedPort === '443');

            // If not secure and not behind HTTPS proxy, redirect
            if (!$isSecure && !$isBehindHttpsProxy) {
                // Only redirect GET requests to avoid form submission issues
                if ($request->isMethod('GET')) {
                    return redirect()->secure($request->getRequestUri(), 301);
                }
            }
        }

        return $next($request);
    }
}