<?php

/*
|--------------------------------------------------------------------------
| VNPay Localhost Debug Script
|--------------------------------------------------------------------------
*/

echo "<h1>🔧 VNPay Localhost Debug</h1>\n";

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
    echo "<p><strong>Auth Status:</strong> " . (auth()->check() ? '✅ Logged in as: ' . auth()->user()->name : '❌ Not logged in') . "</p>";
    echo "</div>";

    echo "<h2>🏦 VNPay Configuration</h2>\n";
    echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>TMN Code:</strong> " . config('services.vnpay.tmn_code') . "</p>";
    echo "<p><strong>Hash Secret:</strong> " . (config('services.vnpay.hash_secret') ? '✅ Set (' . strlen(config('services.vnpay.hash_secret')) . ' chars)' : '❌ Missing') . "</p>";
    echo "<p><strong>VNPay URL:</strong> " . config('services.vnpay.url') . "</p>";
    echo "<p><strong>Return URL:</strong> " . config('services.vnpay.return_url') . "</p>";
    echo "</div>";

    echo "<h2>📦 Recent Orders</h2>\n";
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";

    if (auth()->check()) {
        $orders = \App\Models\Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        if ($orders->count() > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Status</th><th>Payment Method</th><th>Total</th><th>Created</th><th>Actions</th></tr>";

            foreach ($orders as $order) {
                echo "<tr>";
                echo "<td>#{$order->id}</td>";
                echo "<td>{$order->status}</td>";
                echo "<td>{$order->payment_method}</td>";
                echo "<td>" . number_format($order->total, 0, ',', '.') . " VNĐ</td>";
                echo "<td>{$order->created_at->format('H:i d/m')}</td>";
                echo "<td>";

                if ($order->payment_method === 'vnpay' && $order->status === 'pending') {
                    $vnpayUrl = route('payment.vnpay', ['order' => $order->id]);
                    echo "<a href='{$vnpayUrl}' target='_blank' style='background: #007bff; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px;'>Test VNPay</a>";
                }

                $successUrl = route('checkout.success', ['order' => $order->id]);
                echo " <a href='{$successUrl}' target='_blank' style='background: #28a745; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px;'>Success</a>";

                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No orders found for current user.</p>";
        }
    } else {
        echo "<p>❌ Please login first to see orders.</p>";
        echo "<p><a href='/login'>Login Here</a></p>";
    }

    echo "</div>";

    echo "<h2>🧪 VNPay Service Test</h2>\n";
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";

    try {
        $vnpayService = app(\App\Services\VNPayService::class);
        echo "<p>✅ <strong>VNPay Service:</strong> Created successfully</p>";

        // Create test order
        $testOrder = new \App\Models\Order();
        $testOrder->id = 9999;
        $testOrder->total = 100000;

        $paymentUrl = $vnpayService->createPaymentUrl($testOrder, '127.0.0.1');
        echo "<p>✅ <strong>Payment URL Generation:</strong> Success</p>";
        echo "<p><strong>Test URL:</strong> <a href='{$paymentUrl}' target='_blank'>Click to test VNPay</a></p>";

    } catch (Exception $e) {
        echo "<p>❌ <strong>VNPay Service Error:</strong> " . $e->getMessage() . "</p>";
        echo "<pre style='background: #f8f8f8; padding: 10px; font-size: 12px;'>" . $e->getTraceAsString() . "</pre>";
    }

    echo "</div>";

    echo "<h2>🔍 Route Testing</h2>\n";
    echo "<div style='background: #ffeaa7; padding: 15px; border-radius: 5px;'>";

    try {
        $callbackUrl = route('payment.vnpay.callback');
        echo "<p><strong>✅ VNPay Callback:</strong> <a href='{$callbackUrl}?test=1' target='_blank'>{$callbackUrl}</a></p>";
    } catch (Exception $e) {
        echo "<p><strong>❌ VNPay Callback Error:</strong> " . $e->getMessage() . "</p>";
    }

    try {
        $checkoutUrl = route('checkout.index');
        echo "<p><strong>✅ Checkout Page:</strong> <a href='{$checkoutUrl}' target='_blank'>{$checkoutUrl}</a></p>";
    } catch (Exception $e) {
        echo "<p><strong>❌ Checkout Error:</strong> " . $e->getMessage() . "</p>";
    }

    try {
        $cartUrl = route('cart.index');
        echo "<p><strong>✅ Cart Page:</strong> <a href='{$cartUrl}' target='_blank'>{$cartUrl}</a></p>";
    } catch (Exception $e) {
        echo "<p><strong>❌ Cart Error:</strong> " . $e->getMessage() . "</p>";
    }

    echo "</div>";

    echo "<h2>📝 Laravel Logs</h2>\n";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";

    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        $logs = file($logFile);
        $recentLogs = array_slice($logs, -20); // Last 20 lines

        echo "<h4>Recent 20 lines from Laravel log:</h4>";
        echo "<pre style='background: #fff; padding: 10px; border: 1px solid #ddd; max-height: 300px; overflow-y: auto; font-size: 11px;'>";
        foreach ($recentLogs as $log) {
            if (strpos($log, 'VNPay') !== false || strpos($log, 'checkout') !== false) {
                echo "<strong style='color: #e74c3c;'>" . htmlspecialchars($log) . "</strong>";
            } else {
                echo htmlspecialchars($log);
            }
        }
        echo "</pre>";
    } else {
        echo "<p>Laravel log file not found.</p>";
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

    table {
        margin: 10px 0;
    }

    th,
    td {
        padding: 8px 12px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background: #f8f9fa;
        font-weight: bold;
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
</style>