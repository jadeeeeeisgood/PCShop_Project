<?php

/*
|--------------------------------------------------------------------------
| AWS Database Debug Script
|--------------------------------------------------------------------------
|
| Run this on AWS to check database status
| URL: http://your-domain.com/aws-debug.php
|
*/

echo "<h1>🔍 AWS Database Debug</h1>\n";
echo "<p>Environment: " . (app()->environment() ?? 'unknown') . "</p>\n";
echo "<p>URL: " . (request()->getHttpHost() ?? 'unknown') . "</p>\n";

try {
    // Change to Laravel root directory
    chdir(__DIR__ . '/../');

    if (!file_exists('artisan')) {
        throw new Exception('Could not find artisan file. Current directory: ' . getcwd());
    }

    echo "<p>Working directory: " . getcwd() . "</p>\n";

    // Database Connection Test
    echo "<h2>1. Database Connection</h2>\n";
    $output = shell_exec('php artisan tinker --execute="echo \'DB Test: \' . DB::connection()->getName();" 2>&1');
    echo "<pre>$output</pre>\n";

    // Table Counts
    echo "<h2>2. Table Counts</h2>\n";
    $output = shell_exec('php artisan debug:database 2>&1');
    echo "<pre>$output</pre>\n";

    // Product Query Test
    echo "<h2>3. Product Query Test</h2>\n";
    $output = shell_exec('php artisan tinker --execute="
        \$total = App\\Models\\Product::count();
        \$active = App\\Models\\Product::where(\'is_active\', true)->count();
        \$withCategory = App\\Models\\Product::with(\'category\')->where(\'is_active\', true)->count();
        echo \'Total: \' . \$total . PHP_EOL;
        echo \'Active: \' . \$active . PHP_EOL;
        echo \'With Category: \' . \$withCategory . PHP_EOL;
    " 2>&1');
    echo "<pre>$output</pre>\n";

    // Category Test
    echo "<h2>4. Category Test</h2>\n";
    $output = shell_exec('php artisan tinker --execute="
        \$cats = App\\Models\\Category::withCount(\'products\')->get();
        foreach(\$cats as \$cat) {
            echo \$cat->name . \': \' . \$cat->products_count . \' products\' . PHP_EOL;
        }
    " 2>&1');
    echo "<pre>$output</pre>\n";

    // Sample Products
    echo "<h2>5. Sample Products</h2>\n";
    $output = shell_exec('php artisan tinker --execute="
        \$products = App\\Models\\Product::with(\'category\')->where(\'is_active\', true)->take(5)->get();
        foreach(\$products as \$p) {
            echo \$p->id . \': \' . substr(\$p->name, 0, 30) . \' | \' . \$p->category->name . PHP_EOL;
        }
    " 2>&1');
    echo "<pre>$output</pre>\n";

    // Environment Variables
    echo "<h2>6. Environment Info</h2>\n";
    echo "<p>APP_ENV: " . env('APP_ENV') . "</p>\n";
    echo "<p>DB_DATABASE: " . env('DB_DATABASE') . "</p>\n";
    echo "<p>DB_HOST: " . env('DB_HOST') . "</p>\n";

    echo "<h2>✅ Debug Complete</h2>\n";
    echo "<p><a href='/products'>Check Products Page</a></p>\n";

} catch (Exception $e) {
    echo "<h2>❌ Error:</h2>\n";
    echo "<pre>" . $e->getMessage() . "</pre>\n";
}

echo "<p><strong>Note:</strong> Delete this file after debugging.</p>\n";
?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    pre {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        overflow-x: auto;
    }

    h1 {
        color: #007bff;
    }

    h2 {
        color: #28a745;
        border-bottom: 2px solid #28a745;
        padding-bottom: 5px;
    }
</style>