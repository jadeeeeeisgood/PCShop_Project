<?php

if (!function_exists('force_http_url')) {
    /**
     * Force HTTP URL for demo purposes
     */
    function force_http_url($path = '')
    {
        $baseUrl = 'http://www.pcshopvn.id.vn';

        // Use Elastic Beanstalk URL if not on custom domain
        if (!str_contains(request()->getHost(), 'pcshopvn.id.vn')) {
            $baseUrl = 'http://pcshop-final.eba-gm3xqw32.us-east-1.elasticbeanstalk.com';
        }

        return $baseUrl . ($path ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('force_http_route')) {
    /**
     * Generate route URL with proper protocol
     */
    function force_http_route($name, $parameters = [])
    {
        // Just use normal route generation - don't force HTTP
        return route($name, $parameters);
    }
}