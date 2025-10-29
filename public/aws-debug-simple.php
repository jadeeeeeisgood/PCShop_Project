<?php

/*
|--------------------------------------------------------------------------
| Simple AWS Database Debug Script
|--------------------------------------------------------------------------
*/

echo "<h1>🔍 AWS Database Debug</h1>\n";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    echo "<h2>1. Basic PHP Info</h2>\n";
    echo "<p>PHP Version: " . PHP_VERSION . "</p>\n";
    echo "<p>Current Directory: " . getcwd() . "</p>\n";
    echo "<p>Script Path: " . __FILE__ . "</p>\n";

    echo "<h2>2. Laravel Bootstrap Test</h2>\n";

    // Try to bootstrap Laravel
    $laravel_root = dirname(__DIR__);
    echo "<p>Laravel Root: " . $laravel_root . "</p>\n";

    if (file_exists($laravel_root . '/vendor/autoload.php')) {
        echo "<p>✅ Autoload found</p>\n";
        require_once $laravel_root . '/vendor/autoload.php';

        if (file_exists($laravel_root . '/bootstrap/app.php')) {
            echo "<p>✅ Bootstrap found</p>\n";
            $app = require_once $laravel_root . '/bootstrap/app.php';
            $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
            echo "<p>✅ Laravel bootstrapped</p>\n";

            echo "<h2>3. Database Connection Test</h2>\n";

            // Test database connection
            $dbConfig = config('database.connections.mysql');
            echo "<p>Database Host: " . ($dbConfig['host'] ?? 'unknown') . "</p>\n";
            echo "<p>Database Name: " . ($dbConfig['database'] ?? 'unknown') . "</p>\n";
            echo "<p>Database Driver: " . config('database.default') . "</p>\n";

            try {
                $pdo = DB::connection()->getPdo();
                echo "<p>✅ Database Connected</p>\n";

                echo "<h2>4. Table Check</h2>\n";
                $tables = DB::select('SHOW TABLES');
                echo "<p>Total Tables: " . count($tables) . "</p>\n";

                foreach ($tables as $table) {
                    $tableName = array_values((array) $table)[0];
                    if (in_array($tableName, ['products', 'categories', 'users'])) {
                        $count = DB::table($tableName)->count();
                        echo "<p>{$tableName}: {$count} records</p>\n";
                    }
                }

                echo "<h2>5. Products Analysis</h2>\n";
                $totalProducts = DB::table('products')->count();
                $activeProducts = DB::table('products')->where('is_active', 1)->count();
                $productsWithStock = DB::table('products')->where('stock', '>', 0)->count();

                echo "<p>Total Products: {$totalProducts}</p>\n";
                echo "<p>Active Products: {$activeProducts}</p>\n";
                echo "<p>Products with Stock: {$productsWithStock}</p>\n";

                echo "<h2>6. Sample Products</h2>\n";
                $sampleProducts = DB::table('products')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->select('products.id', 'products.name', 'products.is_active', 'products.stock', 'categories.name as category_name')
                    ->limit(5)
                    ->get();

                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
                echo "<tr><th>ID</th><th>Name</th><th>Category</th><th>Active</th><th>Stock</th></tr>\n";
                foreach ($sampleProducts as $product) {
                    echo "<tr>";
                    echo "<td>{$product->id}</td>";
                    echo "<td>" . substr($product->name, 0, 30) . "...</td>";
                    echo "<td>{$product->category_name}</td>";
                    echo "<td>" . ($product->is_active ? 'Yes' : 'No') . "</td>";
                    echo "<td>{$product->stock}</td>";
                    echo "</tr>\n";
                }
                echo "</table>\n";

                echo "<h2>7. Frontend Query Test</h2>\n";
                $frontendQuery = DB::table('products')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->where('products.is_active', 1)
                    ->count();
                echo "<p>Products visible on frontend: {$frontendQuery}</p>\n";

            } catch (Exception $dbError) {
                echo "<p>❌ Database Error: " . $dbError->getMessage() . "</p>\n";
            }

        } else {
            echo "<p>❌ Bootstrap file not found</p>\n";
        }
    } else {
        echo "<p>❌ Autoload file not found</p>\n";
    }

} catch (Exception $e) {
    echo "<h2>❌ Fatal Error:</h2>\n";
    echo "<p>" . $e->getMessage() . "</p>\n";
    echo "<p>File: " . $e->getFile() . "</p>\n";
    echo "<p>Line: " . $e->getLine() . "</p>\n";
}

echo "<h2>8. Environment Variables</h2>\n";
echo "<p>APP_ENV: " . ($_ENV['APP_ENV'] ?? 'not set') . "</p>\n";
echo "<p>DB_HOST: " . ($_ENV['DB_HOST'] ?? 'not set') . "</p>\n";
echo "<p>DB_DATABASE: " . ($_ENV['DB_DATABASE'] ?? 'not set') . "</p>\n";

echo "<h2>✅ Debug Complete</h2>\n";
echo "<p><a href='/products'>Check Products Page</a> | <a href='/'>Homepage</a></p>\n";
echo "<p><strong>Note:</strong> Delete this file after debugging.</p>\n";
?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }

    table {
        margin: 10px 0;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f8f9fa;
    }

    h1 {
        color: #007bff;
    }

    h2 {
        color: #28a745;
        border-bottom: 2px solid #28a745;
        padding-bottom: 5px;
        margin-top: 20px;
    }
</style>