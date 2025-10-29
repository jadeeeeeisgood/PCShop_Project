<?php

/*
|--------------------------------------------------------------------------
| Permanent VNPay Debug - No Auto Cleanup
|--------------------------------------------------------------------------
*/

echo "<h1>🔧 VNPay Final Debug</h1>\n";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Bootstrap Laravel
    $laravel_root = dirname(__DIR__);
    require_once $laravel_root . '/vendor/autoload.php';
    $app = require_once $laravel_root . '/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "<h2>📋 Current Configuration Status</h2>\n";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>Environment:</strong> " . config('app.env') . "</p>";
    echo "<p><strong>APP_URL:</strong> " . config('app.url') . "</p>";
    echo "<p><strong>Current URL:</strong> " . request()->getSchemeAndHttpHost() . "</p>";
    echo "<p><strong>Match Status:</strong> " . (config('app.url') === request()->getSchemeAndHttpHost() ? '✅ Correct' : '❌ Mismatch') . "</p>";
    echo "</div>";

    echo "<h2>🏦 VNPay Configuration</h2>\n";
    echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>TMN Code:</strong> " . config('services.vnpay.tmn_code') . "</p>";
    echo "<p><strong>Hash Secret:</strong> " . (config('services.vnpay.hash_secret') ? '✅ Set (' . strlen(config('services.vnpay.hash_secret')) . ' chars)' : '❌ Missing') . "</p>";
    echo "<p><strong>VNPay URL:</strong> " . config('services.vnpay.url') . "</p>";
    echo "<p><strong>Return URL:</strong> " . config('services.vnpay.return_url') . "</p>";
    echo "</div>";

    echo "<h2>🧪 Route Testing</h2>\n";
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";

    try {
        $callbackUrl = route('payment.vnpay.callback');
        echo "<p><strong>✅ Callback Route:</strong> <a href='{$callbackUrl}?test=1' target='_blank'>{$callbackUrl}</a></p>";
    } catch (Exception $e) {
        echo "<p><strong>❌ Callback Route Error:</strong> " . $e->getMessage() . "</p>";
    }

    try {
        $successUrl = route('checkout.success', ['order' => 1]);
        echo "<p><strong>✅ Success Route:</strong> <a href='{$successUrl}' target='_blank'>{$successUrl}</a></p>";
    } catch (Exception $e) {
        echo "<p><strong>❌ Success Route Error:</strong> " . $e->getMessage() . "</p>";
    }

    echo "</div>";

    echo "<h2>🔍 VNPay Flow Debug</h2>\n";
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";

    // Test if VNPay service can be created
    try {
        $vnpay = app(\App\Services\VNPayService::class);
        echo "<p>✅ <strong>VNPay Service:</strong> Created successfully</p>";

        // Create a dummy order for testing
        $dummyOrder = new \App\Models\Order();
        $dummyOrder->id = 999;
        $dummyOrder->total = 100000; // 100k VND

        try {
            $paymentUrl = $vnpay->createPaymentUrl($dummyOrder, '127.0.0.1');
            echo "<p>✅ <strong>Payment URL Generation:</strong> Success</p>";
            echo "<p><strong>Sample URL:</strong> " . substr($paymentUrl, 0, 100) . "...</p>";
        } catch (Exception $e) {
            echo "<p>❌ <strong>Payment URL Generation Error:</strong> " . $e->getMessage() . "</p>";
        }

    } catch (Exception $e) {
        echo "<p>❌ <strong>VNPay Service Error:</strong> " . $e->getMessage() . "</p>";
    }

    echo "</div>";

    echo "<h2>📝 Environment Variables Raw Check</h2>\n";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 12px;'>";
    $envVars = [
        'APP_URL' => env('APP_URL'),
        'APP_ENV' => env('APP_ENV'),
        'VNPAY_TMN_CODE' => env('VNPAY_TMN_CODE'),
        'VNPAY_HASH_SECRET' => env('VNPAY_HASH_SECRET') ? '***' . substr(env('VNPAY_HASH_SECRET'), -4) : 'NULL',
        'VNPAY_URL' => env('VNPAY_URL'),
        'VNPAY_RETURN_URL' => env('VNPAY_RETURN_URL', 'Using default'),
    ];

    foreach ($envVars as $key => $value) {
        echo "<p><strong>{$key}:</strong> {$value}</p>";
    }
    echo "</div>";

    echo "<h2>🚨 Common Issues & Solutions</h2>\n";
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
    echo "<h4>If VNPay still redirects to cart:</h4>";
    echo "<ol>";
    echo "<li><strong>Check Laravel Logs:</strong> Look for VNPay callback entries</li>";
    echo "<li><strong>VNPay Sandbox Issue:</strong> Sandbox might not callback to non-HTTPS URLs</li>";
    echo "<li><strong>Firewall/Security Groups:</strong> AWS might block external callbacks</li>";
    echo "<li><strong>Session Issues:</strong> Cross-domain session problems</li>";
    echo "</ol>";
    echo "</div>";

    echo "<h2>💡 Manual Test VNPay Callback</h2>\n";
    echo "<div style='background: #cff4fc; padding: 15px; border-radius: 5px;'>";
    echo "<p>Test VNPay callback manually:</p>";
    echo "<p><a href='" . route('payment.vnpay.callback') . "?vnp_TxnRef=999_test&vnp_ResponseCode=00&vnp_TransactionStatus=00' target='_blank'>Simulate Successful VNPay Callback</a></p>";
    echo "<p><em>This will test if the callback route works at all</em></p>";
    echo "</div>";

    echo "<h2>🎯 Next Debug Steps</h2>\n";
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";

    if (config('app.url') === 'http://localhost') {
        echo "<p>❌ <strong>Critical:</strong> APP_URL is still localhost!</p>";
        echo "<p>Environment variables are not being applied correctly.</p>";
        echo "<p>Try:</p>";
        echo "<ul>";
        echo "<li>Check AWS EB Environment Configuration again</li>";
        echo "<li>Restart the application after setting env vars</li>";
        echo "<li>Run 'php artisan config:clear' again</li>";
        echo "</ul>";
    } else {
        echo "<p>✅ APP_URL is correctly set to AWS domain</p>";
        echo "<p>VNPay configuration looks good. Issue might be:</p>";
        echo "<ul>";
        echo "<li>VNPay sandbox callback limitations</li>";
        echo "<li>AWS security group blocking external requests</li>";
        echo "<li>Laravel route/middleware issues</li>";
        echo "</ul>";
    }

    echo "</div>";

} catch (Exception $e) {
    echo "<h2>❌ Error:</h2>\n";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>\n";
    echo "<pre style='color: red; font-size: 12px;'>" . $e->getTraceAsString() . "</pre>";
}

?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 1200px;
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

    pre {
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    ol li,
    ul li {
        margin: 8px 0;
    }
</style>