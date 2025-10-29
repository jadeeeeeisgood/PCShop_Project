<?php

/*
|--------------------------------------------------------------------------
| AWS Environment Variables Setup for VNPay
|--------------------------------------------------------------------------
*/

echo "<h1>🔧 AWS Environment Setup</h1>\n";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Bootstrap Laravel
    $laravel_root = dirname(__DIR__);
    require_once $laravel_root . '/vendor/autoload.php';
    $app = require_once $laravel_root . '/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "<h2>🌐 Current Domain Detection</h2>\n";

    $currentDomain = request()->getSchemeAndHttpHost();
    $correctAppUrl = $currentDomain;

    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>Detected Domain:</strong> {$currentDomain}</p>";
    echo "<p><strong>Should set APP_URL to:</strong> {$correctAppUrl}</p>";
    echo "</div>";

    echo "<h2>📝 Required Environment Variables for AWS</h2>\n";
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
    echo "<h4>Set these in AWS Elastic Beanstalk Environment Variables:</h4>";
    echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 3px;'>";
    echo "APP_URL={$correctAppUrl}\n";
    echo "VNPAY_TMN_CODE=V22NS9SB\n";
    echo "VNPAY_HASH_SECRET=4WFQZ7N3KT6KFEUJ2JA1IM431N8STD3O\n";
    echo "VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html\n";
    echo "</pre>";
    echo "</div>";

    echo "<h2>🎯 Steps to Fix VNPay on AWS</h2>\n";
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";
    echo "<ol>";
    echo "<li><strong>Go to AWS Elastic Beanstalk Console</strong></li>";
    echo "<li><strong>Select your application:</strong> PCShop</li>";
    echo "<li><strong>Go to:</strong> Configuration → Software → Environment properties</li>";
    echo "<li><strong>Add these variables:</strong>";
    echo "<ul>";
    echo "<li>APP_URL = {$correctAppUrl}</li>";
    echo "<li>VNPAY_TMN_CODE = V22NS9SB</li>";
    echo "<li>VNPAY_HASH_SECRET = 4WFQZ7N3KT6KFEUJ2JA1IM431N8STD3O</li>";
    echo "<li>VNPAY_URL = https://sandbox.vnpayment.vn/paymentv2/vpcpay.html</li>";
    echo "</ul></li>";
    echo "<li><strong>Click Apply</strong> (AWS will restart the application)</li>";
    echo "<li><strong>After restart, run:</strong> <code>php artisan config:clear</code></li>";
    echo "<li><strong>Test VNPay again</strong></li>";
    echo "</ol>";
    echo "</div>";

    echo "<h2>🔗 Expected Results After Fix</h2>\n";
    echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>APP_URL will be:</strong> {$correctAppUrl}</p>";
    echo "<p><strong>VNPay Return URL will be:</strong> {$correctAppUrl}/payment/vnpay/callback</p>";
    echo "<p><strong>Success URL will be:</strong> {$correctAppUrl}/checkout/success/[order-id]</p>";
    echo "</div>";

    echo "<h2>📱 Alternative: Manual Environment Variables</h2>\n";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
    echo "<p>If you can't access AWS Console, create a <code>.env.production</code> file with:</p>";
    echo "<pre style='background: #fff; padding: 10px; border: 1px solid #ddd;'>";
    echo "APP_URL={$correctAppUrl}\n";
    echo "VNPAY_TMN_CODE=V22NS9SB\n";
    echo "VNPAY_HASH_SECRET=4WFQZ7N3KT6KFEUJ2JA1IM431N8STD3O\n";
    echo "VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html\n";
    echo "</pre>";
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

    pre {
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    code {
        background: #f8f9fa;
        padding: 2px 4px;
        border-radius: 3px;
        font-family: monospace;
        color: #e83e8c;
    }

    ol li {
        margin: 10px 0;
    }

    ul li {
        margin: 5px 0;
    }
</style>