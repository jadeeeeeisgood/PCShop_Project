<?php

/*
|--------------------------------------------------------------------------
| VNPay Configuration Debug Script for AWS
|--------------------------------------------------------------------------
*/

echo "<h1>🔧 VNPay Configuration Debug</h1>\n";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Bootstrap Laravel
    $laravel_root = dirname(__DIR__);
    require_once $laravel_root . '/vendor/autoload.php';
    $app = require_once $laravel_root . '/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "<h2>📋 Current Environment</h2>\n";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>Environment:</strong> " . config('app.env') . "</p>";
    echo "<p><strong>APP_URL:</strong> " . config('app.url') . "</p>";
    echo "<p><strong>Current URL:</strong> " . request()->getSchemeAndHttpHost() . "</p>";
    echo "</div>";

    echo "<h2>🏦 VNPay Configuration</h2>\n";
    echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>TMN Code:</strong> " . config('services.vnpay.tmn_code') . "</p>";
    echo "<p><strong>VNPay URL:</strong> " . config('services.vnpay.url') . "</p>";
    echo "<p><strong>Return URL:</strong> " . config('services.vnpay.return_url') . "</p>";
    echo "<p><strong>Hash Secret:</strong> " . (config('services.vnpay.hash_secret') ? '✅ Set' : '❌ Missing') . "</p>";
    echo "</div>";

    echo "<h2>🧪 Test URLs</h2>\n";
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";

    // Test callback route
    try {
        $callbackUrl = route('payment.vnpay.callback');
        echo "<p><strong>Callback Route:</strong> <a href='{$callbackUrl}' target='_blank'>{$callbackUrl}</a></p>";
    } catch (Exception $e) {
        echo "<p><strong>Callback Route:</strong> ❌ Error - " . $e->getMessage() . "</p>";
    }

    // Test success route  
    try {
        $successUrl = route('checkout.success', ['order' => 1]);
        echo "<p><strong>Success Route:</strong> <a href='{$successUrl}' target='_blank'>{$successUrl}</a></p>";
    } catch (Exception $e) {
        echo "<p><strong>Success Route:</strong> ❌ Error - " . $e->getMessage() . "</p>";
    }

    echo "</div>";

    echo "<h2>📝 Environment Variables</h2>\n";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 12px;'>";
    $envVars = [
        'APP_URL' => env('APP_URL'),
        'VNPAY_TMN_CODE' => env('VNPAY_TMN_CODE'),
        'VNPAY_URL' => env('VNPAY_URL'),
        'VNPAY_RETURN_URL' => env('VNPAY_RETURN_URL', 'Using default'),
    ];

    foreach ($envVars as $key => $value) {
        echo "<p><strong>{$key}:</strong> {$value}</p>";
    }
    echo "</div>";

    echo "<h2>✅ Recommendations for AWS</h2>\n";
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";
    echo "<h4>🚀 Deploy Steps:</h4>";
    echo "<ol>";
    echo "<li>Ensure <code>APP_URL</code> is set to your AWS domain in production</li>";
    echo "<li>VNPay return URL will automatically use: <code>{APP_URL}/payment/vnpay/callback</code></li>";
    echo "<li>Test callback URL: <code>" . config('services.vnpay.return_url') . "</code></li>";
    echo "<li>Clear config cache on AWS: <code>php artisan config:clear</code></li>";
    echo "</ol>";
    echo "</div>";

} catch (Exception $e) {
    echo "<h2>❌ Error:</h2>\n";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>\n";
}

?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
        line-height: 1.6;
    }

    h1 {
        color: #007bff;
        text-align: center;
    }

    h2 {
        color: #28a745;
        border-bottom: 2px solid #28a745;
        padding-bottom: 5px;
        margin-top: 30px;
    }

    code {
        background: #f8f9fa;
        padding: 2px 4px;
        border-radius: 3px;
        font-family: monospace;
        color: #e83e8c;
    }

    a {
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>