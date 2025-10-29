<?php

/*
|--------------------------------------------------------------------------
| Database Debug & Analysis Script
|--------------------------------------------------------------------------
*/

echo "<h1>🔍 Database Debug & Analysis</h1>\n";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Bootstrap Laravel
    $laravel_root = dirname(__DIR__);
    require_once $laravel_root . '/vendor/autoload.php';
    $app = require_once $laravel_root . '/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "<h2>1. Database Connection Info</h2>\n";
    $dbConfig = config('database.connections.mysql');
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>Host:</strong> " . $dbConfig['host'] . "</p>\n";
    echo "<p><strong>Database:</strong> " . $dbConfig['database'] . "</p>\n";
    echo "<p><strong>Username:</strong> " . $dbConfig['username'] . "</p>\n";
    echo "<p><strong>Port:</strong> " . $dbConfig['port'] . "</p>\n";
    echo "</div>";

    echo "<h2>2. Database Structure Analysis</h2>\n";

    // Check users table structure
    echo "<h3>👥 Users Table Structure:</h3>\n";
    $userColumns = DB::select("DESCRIBE users");
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($userColumns as $col) {
        echo "<tr>";
        echo "<td>{$col->Field}</td>";
        echo "<td>{$col->Type}</td>";
        echo "<td>{$col->Null}</td>";
        echo "<td>{$col->Key}</td>";
        echo "<td>{$col->Default}</td>";
        echo "<td>{$col->Extra}</td>";
        echo "</tr>";
    }
    echo "</table><br>";

    // Check products table structure
    echo "<h3>🛍️ Products Table Structure:</h3>\n";
    $productColumns = DB::select("DESCRIBE products");
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($productColumns as $col) {
        echo "<tr>";
        echo "<td>{$col->Field}</td>";
        echo "<td>{$col->Type}</td>";
        echo "<td>{$col->Null}</td>";
        echo "<td>{$col->Key}</td>";
        echo "<td>{$col->Default}</td>";
        echo "<td>{$col->Extra}</td>";
        echo "</tr>";
    }
    echo "</table><br>";

    echo "<h2>3. Data Count Analysis</h2>\n";
    $tables = ['users', 'categories', 'products', 'orders', 'order_items', 'cart_items'];

    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
    foreach ($tables as $table) {
        try {
            $count = DB::table($table)->count();
            echo "<p><strong>{$table}:</strong> {$count} records</p>\n";
        } catch (Exception $e) {
            echo "<p><strong>{$table}:</strong> <span style='color: red;'>Error - {$e->getMessage()}</span></p>\n";
        }
    }
    echo "</div>";

    echo "<h2>4. Sample Data Preview</h2>\n";

    // Show sample users
    echo "<h3>👥 Sample Users (first 3):</h3>\n";
    $sampleUsers = DB::table('users')->limit(3)->get();
    if ($sampleUsers->count() > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        $firstUser = $sampleUsers->first();
        echo "<tr>";
        foreach (get_object_vars($firstUser) as $key => $value) {
            echo "<th>{$key}</th>";
        }
        echo "</tr>";

        foreach ($sampleUsers as $user) {
            echo "<tr>";
            foreach (get_object_vars($user) as $key => $value) {
                echo "<td>" . htmlspecialchars(substr($value ?? '', 0, 50)) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "<p>No users found</p>";
    }

    // Show sample products
    echo "<h3>🛍️ Sample Products (first 3):</h3>\n";
    $sampleProducts = DB::table('products')->limit(3)->get();
    if ($sampleProducts->count() > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Category ID</th><th>Price</th><th>Stock</th><th>Created</th></tr>";

        foreach ($sampleProducts as $product) {
            echo "<tr>";
            echo "<td>{$product->id}</td>";
            echo "<td>" . htmlspecialchars(substr($product->name, 0, 30)) . "</td>";
            echo "<td>{$product->category_id}</td>";
            echo "<td>" . number_format($product->price) . "</td>";
            echo "<td>{$product->stock}</td>";
            echo "<td>{$product->created_at}</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "<p>No products found</p>";
    }

    // Check if this is the correct database
    echo "<h2>5. Database Verification</h2>\n";
    $productNames = DB::table('products')->pluck('name')->take(5);
    echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px;'>";
    echo "<h4>🔍 Current Product Names:</h4>";
    foreach ($productNames as $name) {
        echo "<p>• " . htmlspecialchars($name) . "</p>";
    }
    echo "</div>";

    // Suggest actions
    echo "<h2>6. Recommendations</h2>\n";
    $productCount = DB::table('products')->count();

    if ($productCount < 10) {
        echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
        echo "<h4>⚠️ Low Product Count Detected</h4>";
        echo "<p>Only {$productCount} products found. This might indicate:</p>";
        echo "<ul>";
        echo "<li>🗄️ Wrong database connection</li>";
        echo "<li>📊 Data not fully migrated</li>";
        echo "<li>🔄 Need to run seeders</li>";
        echo "</ul>";
        echo "<p><strong>Suggestion:</strong> Check if you're connected to the right database</p>";
        echo "</div>";
    } else {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";
        echo "<h4>✅ Normal Product Count</h4>";
        echo "<p>{$productCount} products found - this looks good!</p>";
        echo "</div>";
    }

} catch (Exception $e) {
    echo "<h2>❌ Error:</h2>\n";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>\n";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
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

    h3 {
        color: #6c757d;
        margin-top: 20px;
    }

    table {
        font-size: 12px;
        margin: 10px 0;
    }

    th {
        background: #f8f9fa;
        padding: 8px;
        text-align: left;
    }

    td {
        padding: 6px 8px;
        border: 1px solid #ddd;
    }

    pre {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        overflow-x: auto;
        font-size: 12px;
    }
</style>