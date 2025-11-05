<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceCleanUrls
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If request comes with index.php, redirect to clean URL
        if ($request->getPathInfo() === '/index.php' || str_starts_with($request->getPathInfo(), '/index.php/')) {
            // Extract the actual path without index.php
            $cleanPath = str_replace('/index.php', '', $request->getRequestUri());

            // If we have a query string, preserve it
            $queryString = $request->getQueryString();
            if ($queryString) {
                $cleanPath .= '?' . $queryString;
            }

            // Redirect to clean URL
            return redirect($cleanPath, 301);
        }

        return $next($request);
    }
}