<?php

/*
|--------------------------------------------------------------------------
| Final System Check Before AWS Deployment
|--------------------------------------------------------------------------
*/

echo "<h1>🚀 Final System Check - Ready for AWS?</h1>\n";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Bootstrap Laravel
    $laravel_root = dirname(__DIR__);
    require_once $laravel_root . '/vendor/autoload.php';
    $app = require_once $laravel_root . '/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "<h2>✅ System Status Overview</h2>\n";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>Environment:</strong> " . config('app.env') . "</p>";
    echo "<p><strong>APP_URL:</strong> " . config('app.url') . "</p>";
    echo "<p><strong>Debug Mode:</strong> " . (config('app.debug') ? '✅ ON (for development)' : '❌ OFF') . "</p>";
    echo "<p><strong>Database:</strong> " . config('database.default') . "</p>";
    echo "</div>";

    // Database connectivity test
    echo "<h2>🗄️ Database Check</h2>\n";
    echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px;'>";

    try {
        DB::connection()->getPdo();
        echo "<p>✅ <strong>Database Connection:</strong> Success</p>";

        // Count records
        $users = DB::table('users')->count();
        $categories = DB::table('categories')->count();
        $products = DB::table('products')->count();
        $orders = DB::table('orders')->count();

        echo "<p>👥 <strong>Users:</strong> {$users}</p>";
        echo "<p>📂 <strong>Categories:</strong> {$categories}</p>";
        echo "<p>🛍️ <strong>Products:</strong> {$products}</p>";
        echo "<p>📦 <strong>Orders:</strong> {$orders}</p>";

        if ($products >= 25 && $categories >= 5) {
            echo "<p style='color: green;'>✅ Good amount of sample data</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Consider adding more sample data</p>";
        }

    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ <strong>Database Error:</strong> " . $e->getMessage() . "</p>";
    }

    echo "</div>";

    // Authentication Check
    echo "<h2>🔐 Authentication Status</h2>\n";
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";

    if (auth()->check()) {
        $user = auth()->user();
        echo "<p>✅ <strong>Currently Logged In:</strong> {$user->name} ({$user->role})</p>";
        echo "<p><strong>Email:</strong> {$user->email}</p>";
    } else {
        echo "<p>ℹ️ Not currently logged in (this is normal for testing)</p>";
    }

    echo "</div>";

    // VNPay Configuration Check
    echo "<h2>💳 VNPay Configuration</h2>\n";
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";

    $vnpayConfig = config('services.vnpay');
    echo "<p><strong>TMN Code:</strong> " . ($vnpayConfig['tmn_code'] ?? 'Not set') . "</p>";
    echo "<p><strong>Hash Secret:</strong> " . (isset($vnpayConfig['hash_secret']) && !empty($vnpayConfig['hash_secret']) ? '✅ Set (' . strlen($vnpayConfig['hash_secret']) . ' chars)' : '❌ Missing') . "</p>";
    echo "<p><strong>VNPay URL:</strong> " . ($vnpayConfig['url'] ?? 'Not set') . "</p>";
    echo "<p><strong>Return URL:</strong> " . ($vnpayConfig['return_url'] ?? 'Not set') . "</p>";

    if (!empty($vnpayConfig['tmn_code']) && !empty($vnpayConfig['hash_secret'])) {
        echo "<p style='color: green;'>✅ VNPay configuration looks good</p>";
    } else {
        echo "<p style='color: red;'>❌ VNPay configuration incomplete</p>";
    }

    echo "</div>";

    // Routes Check
    echo "<h2>🛣️ Critical Routes Check</h2>\n";
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";

    $criticalRoutes = [
        'welcome' => 'Home page',
        'products.index' => 'Products listing',
        'cart.index' => 'Shopping cart',
        'checkout.index' => 'Checkout page',
        'checkout.success' => 'Order success page',
        'payment.vnpay.callback' => 'VNPay callback',
        'admin.dashboard' => 'Admin dashboard',
        'admin.orders.index' => 'Admin orders',
        'admin.users.change-password' => 'Admin user password change'
    ];

    foreach ($criticalRoutes as $routeName => $description) {
        try {
            if ($routeName === 'checkout.success') {
                $url = route($routeName, ['order' => 1]);
            } elseif ($routeName === 'admin.users.change-password') {
                $url = route($routeName, ['user' => 1]);
            } else {
                $url = route($routeName);
            }
            echo "<p>✅ <strong>{$description}:</strong> {$url}</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ <strong>{$description}:</strong> Route not found</p>";
        }
    }

    echo "</div>";

    // File Structure Check
    echo "<h2>📁 Important Files Check</h2>\n";
    echo "<div style='background: #cff4fc; padding: 15px; border-radius: 5px;'>";

    $importantFiles = [
        'storage/app/public' => 'Public storage directory',
        '.env' => 'Environment configuration',
        'composer.json' => 'PHP dependencies',
        'package.json' => 'NPM dependencies',
        'database/migrations' => 'Database migrations',
        'public/.htaccess' => 'Apache configuration'
    ];

    foreach ($importantFiles as $file => $description) {
        $fullPath = base_path($file);
        if (file_exists($fullPath)) {
            echo "<p>✅ <strong>{$description}:</strong> Present</p>";
        } else {
            echo "<p style='color: red;'>❌ <strong>{$description}:</strong> Missing</p>";
        }
    }

    echo "</div>";

    // Features Testing Summary
    echo "<h2>🧪 Features Testing Summary</h2>\n";
    echo "<div style='background: #e2e3e5; padding: 15px; border-radius: 5px;'>";
    echo "<h4>Manual Testing Checklist:</h4>";
    echo "<div style='display: grid; grid-template-columns: 1fr 1fr; gap: 20px;'>";

    echo "<div>";
    echo "<h5>🛒 E-commerce Features:</h5>";
    echo "<ul>";
    echo "<li>🔲 Browse products by category</li>";
    echo "<li>🔲 Search products</li>";
    echo "<li>🔲 Add to cart (guest & user)</li>";
    echo "<li>🔲 Update cart quantities</li>";
    echo "<li>🔲 Remove from cart</li>";
    echo "<li>🔲 User registration & login</li>";
    echo "<li>🔲 User profile management</li>";
    echo "</ul>";
    echo "</div>";

    echo "<div>";
    echo "<h5>💰 Payment Features:</h5>";
    echo "<ul>";
    echo "<li>🔲 COD checkout process</li>";
    echo "<li>🔲 VNPay checkout process</li>";
    echo "<li>🔲 VNPay sandbox payment</li>";
    echo "<li>🔲 Order confirmation email</li>";
    echo "<li>🔲 Order success page</li>";
    echo "<li>🔲 Admin COD completion</li>";
    echo "</ul>";
    echo "</div>";

    echo "</div>";

    echo "<div style='margin-top: 20px;'>";
    echo "<h5>👨‍💼 Admin Features:</h5>";
    echo "<ul style='display: grid; grid-template-columns: 1fr 1fr; gap: 10px; list-style: none; padding: 0;'>";
    echo "<li>🔲 Admin dashboard access</li>";
    echo "<li>🔲 Product management (CRUD)</li>";
    echo "<li>🔲 Category management</li>";
    echo "<li>🔲 Order management</li>";
    echo "<li>🔲 User management</li>";
    echo "<li>🔲 Order status updates</li>";
    echo "<li>🔲 COD order completion</li>";
    echo "<li>🔲 User password changes</li>";
    echo "</ul>";
    echo "</div>";

    echo "</div>";

    // AWS Deployment Checklist
    echo "<h2>☁️ AWS Deployment Checklist</h2>\n";
    echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
    echo "<h4>Before Deployment:</h4>";
    echo "<ol>";
    echo "<li><strong>Update .env for production:</strong>";
    echo "<ul>";
    echo "<li>APP_ENV=production</li>";
    echo "<li>APP_DEBUG=false</li>";
    echo "<li>APP_URL=https://your-aws-domain.com</li>";
    echo "<li>Database credentials for AWS RDS</li>";
    echo "</ul></li>";
    echo "<li><strong>Run migrations on AWS:</strong> <code>php artisan migrate</code></li>";
    echo "<li><strong>Import data on AWS:</strong> Use aws-complete-import.php</li>";
    echo "<li><strong>Set AWS environment variables:</strong>";
    echo "<ul>";
    echo "<li>VNPAY_TMN_CODE</li>";
    echo "<li>VNPAY_HASH_SECRET</li>";
    echo "<li>APP_URL (AWS domain)</li>";
    echo "</ul></li>";
    echo "<li><strong>Clear caches on AWS:</strong> <code>php artisan config:clear</code></li>";
    echo "</ol>";
    echo "</div>";

    // Final Recommendations
    echo "<h2>🎯 Final Recommendations</h2>\n";
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";

    $recommendations = [];

    if ($products < 20) {
        $recommendations[] = "Consider adding more sample products (current: {$products})";
    }

    if (config('app.debug')) {
        $recommendations[] = "Set APP_DEBUG=false for production deployment";
    }

    if (!auth()->check()) {
        $recommendations[] = "Test admin login before deployment";
    }

    if (empty($recommendations)) {
        echo "<p style='color: green; font-size: 18px; font-weight: bold;'>🎉 System looks ready for AWS deployment!</p>";
        echo "<p>All major components are configured and should work properly.</p>";
    } else {
        echo "<h4>⚠️ Consider these improvements:</h4>";
        echo "<ul>";
        foreach ($recommendations as $rec) {
            echo "<li>{$rec}</li>";
        }
        echo "</ul>";
    }

    echo "</div>";

    // Quick Test Links
    echo "<h2>🔗 Quick Test Links</h2>\n";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
    echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 10px;'>";

    $testLinks = [
        '/' => 'Home Page',
        '/products' => 'Products',
        '/cart' => 'Cart',
        '/login' => 'Login',
        '/register' => 'Register',
        '/admin' => 'Admin Dashboard'
    ];

    foreach ($testLinks as $path => $label) {
        $fullUrl = config('app.url') . $path;
        echo "<a href='{$fullUrl}' target='_blank' style='display: block; padding: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; text-align: center;'>{$label}</a>";
    }

    echo "</div>";
    echo "</div>";

} catch (Exception $e) {
    echo "<h2>❌ System Error:</h2>\n";
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
        margin-bottom: 30px;
    }

    h2 {
        color: #28a745;
        border-bottom: 2px solid #28a745;
        padding-bottom: 5px;
        margin-top: 30px;
    }

    h4,
    h5 {
        color: #495057;
        margin-top: 20px;
    }

    ul,
    ol {
        margin: 10px 0;
    }

    li {
        margin: 5px 0;
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

    .grid {
        display: grid;
        gap: 20px;
    }
</style>