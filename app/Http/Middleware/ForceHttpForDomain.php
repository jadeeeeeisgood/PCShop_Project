<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpForDomain
{
    /**
     * Force HTTP for pcshopvn.id.vn domain - redirect HTTPS to HTTP
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip health checks
        if ($request->header('User-Agent') === 'ELB-HealthChecker/2.0') {
            return $next($request);
        }

        // Force HTTP for pcshopvn.id.vn domain
        if ($request->getHost() === 'www.pcshopvn.id.vn' || $request->getHost() === 'pcshopvn.id.vn') {

            // If request is HTTPS, redirect to HTTP
            if ($request->isSecure() || $request->header('HTTP_X_FORWARDED_PROTO') === 'https') {
                $httpUrl = 'http://' . $request->getHost() . $request->getRequestUri();
                return redirect($httpUrl, 301);
            }

            // Force all generated URLs to use HTTP
            URL::forceScheme('http');
            URL::forceRootUrl('http://www.pcshopvn.id.vn');
        }

        return $next($request);
    }
}