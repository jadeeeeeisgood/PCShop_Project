<?php

/*
|--------------------------------------------------------------------------
| AWS Config Clear & Verification
|--------------------------------------------------------------------------
*/

echo "<h1>🔄 AWS Config Clear & Test</h1>\n";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Bootstrap Laravel
    $laravel_root = dirname(__DIR__);
    require_once $laravel_root . '/vendor/autoload.php';
    $app = require_once $laravel_root . '/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "<h2>⚡ Running Commands</h2>\n";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; font-family: monospace;'>";

    // Clear config cache
    try {
        Artisan::call('config:clear');
        echo "<p>✅ <strong>config:clear:</strong> " . Artisan::output() . "</p>";
    } catch (Exception $e) {
        echo "<p>❌ <strong>config:clear failed:</strong> " . $e->getMessage() . "</p>";
    }

    // Clear route cache
    try {
        Artisan::call('route:clear');
        echo "<p>✅ <strong>route:clear:</strong> " . Artisan::output() . "</p>";
    } catch (Exception $e) {
        echo "<p>❌ <strong>route:clear failed:</strong> " . $e->getMessage() . "</p>";
    }

    // Clear view cache
    try {
        Artisan::call('view:clear');
        echo "<p>✅ <strong>view:clear:</strong> " . Artisan::output() . "</p>";
    } catch (Exception $e) {
        echo "<p>❌ <strong>view:clear failed:</strong> " . $e->getMessage() . "</p>";
    }

    echo "</div>";

    echo "<h2>🔍 Verification After Clear</h2>\n";
    echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>APP_URL:</strong> " . config('app.url') . "</p>";
    echo "<p><strong>Environment:</strong> " . config('app.env') . "</p>";
    echo "<p><strong>VNPay Return URL:</strong> " . config('services.vnpay.return_url') . "</p>";
    echo "<p><strong>Current Domain:</strong> " . request()->getSchemeAndHttpHost() . "</p>";
    echo "</div>";

    // Test route generation
    echo "<h2>🧪 Route Testing</h2>\n";
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";

    try {
        $callbackRoute = route('payment.vnpay.callback');
        echo "<p>✅ <strong>VNPay Callback Route:</strong> {$callbackRoute}</p>";
    } catch (Exception $e) {
        echo "<p>❌ <strong>VNPay Callback Route Error:</strong> " . $e->getMessage() . "</p>";
    }

    try {
        $successRoute = route('checkout.success', ['order' => 999]);
        echo "<p>✅ <strong>Success Route:</strong> {$successRoute}</p>";
    } catch (Exception $e) {
        echo "<p>❌ <strong>Success Route Error:</strong> " . $e->getMessage() . "</p>";
    }

    echo "</div>";

    echo "<h2>🎯 Next Steps</h2>\n";
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
    if (config('app.url') === 'http://localhost') {
        echo "<p>⚠️ <strong>APP_URL is still localhost!</strong></p>";
        echo "<p>You need to set environment variables in AWS Elastic Beanstalk first.</p>";
        echo "<p>Go to: <a href='aws-env-setup.php'>AWS Environment Setup Guide</a></p>";
    } else {
        echo "<p>✅ <strong>APP_URL looks good!</strong></p>";
        echo "<p>VNPay should now work correctly.</p>";
        echo "<p>Test VNPay payment now!</p>";
    }
    echo "</div>";

    // Auto cleanup after success
    if (config('app.url') !== 'http://localhost') {
        echo "<p style='color: #666; font-size: 12px;'>🧹 Auto-cleanup: Removing debug files...</p>";
        @unlink(__FILE__);
        @unlink(__DIR__ . '/aws-env-setup.php');
        @unlink(__DIR__ . '/debug-vnpay-config.php');
    }

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

    a {
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>