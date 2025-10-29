<?php

/*
|--------------------------------------------------------------------------
| Complete Database Export Script
|--------------------------------------------------------------------------
*/

echo "<h1>📊 Complete Database Export</h1>\n";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Bootstrap Laravel
    $laravel_root = dirname(__DIR__);
    require_once $laravel_root . '/vendor/autoload.php';
    $app = require_once $laravel_root . '/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "<h2>1. Local Database Analysis</h2>\n";

    // Check all tables
    $categories = DB::table('categories')->count();
    $products = DB::table('products')->count();
    $users = DB::table('users')->count();
    $orders = DB::table('orders')->count();
    $cartItems = DB::table('cart_items')->count();
    $orderItems = DB::table('order_items')->count();

    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
    echo "<h3>📋 Current Data Count:</h3>";
    echo "<p>👥 Users: <strong>{$users}</strong></p>";
    echo "<p>📂 Categories: <strong>{$categories}</strong></p>";
    echo "<p>🛍️ Products: <strong>{$products}</strong></p>";
    echo "<p>🛒 Cart Items: <strong>{$cartItems}</strong></p>";
    echo "<p>📦 Orders: <strong>{$orders}</strong></p>";
    echo "<p>📋 Order Items: <strong>{$orderItems}</strong></p>";
    echo "</div>";

    if (isset($_POST['export_type'])) {
        $exportType = $_POST['export_type'];

        echo "<h2>2. Exporting Data ({$exportType})</h2>\n";

        $exportSql = "";

        if ($exportType === 'users' || $exportType === 'all') {
            echo "<p>👥 Exporting users...</p>\n";
            $users = DB::table('users')->get();
            foreach ($users as $user) {
                $exportSql .= "INSERT INTO users (id, name, email, email_verified_at, password, role, remember_token, created_at, updated_at) VALUES (";
                $exportSql .= $user->id . ", ";
                $exportSql .= "'" . addslashes($user->name) . "', ";
                $exportSql .= "'" . addslashes($user->email) . "', ";
                $exportSql .= ($user->email_verified_at ? "'" . $user->email_verified_at . "'" : "NULL") . ", ";
                $exportSql .= "'" . addslashes($user->password) . "', ";
                $exportSql .= "'" . $user->role . "', ";
                $exportSql .= ($user->remember_token ? "'" . addslashes($user->remember_token) . "'" : "NULL") . ", ";
                $exportSql .= "'" . $user->created_at . "', ";
                $exportSql .= "'" . $user->updated_at . "'";
                $exportSql .= ");\n";
            }
            echo "<p>✅ Users exported: {$users->count()}</p>\n";
        }

        if ($exportType === 'categories' || $exportType === 'all') {
            echo "<p>📂 Exporting categories...</p>\n";
            $categories = DB::table('categories')->get();
            foreach ($categories as $category) {
                $exportSql .= "INSERT INTO categories (id, name, slug, description, image, is_active, created_at, updated_at) VALUES (";
                $exportSql .= $category->id . ", ";
                $exportSql .= "'" . addslashes($category->name) . "', ";
                $exportSql .= "'" . addslashes($category->slug) . "', ";
                $exportSql .= ($category->description ? "'" . addslashes($category->description) . "'" : "NULL") . ", ";
                $exportSql .= ($category->image ? "'" . addslashes($category->image) . "'" : "NULL") . ", ";
                $exportSql .= $category->is_active . ", ";
                $exportSql .= "'" . $category->created_at . "', ";
                $exportSql .= "'" . $category->updated_at . "'";
                $exportSql .= ");\n";
            }
            echo "<p>✅ Categories exported: {$categories->count()}</p>\n";
        }

        if ($exportType === 'products' || $exportType === 'all') {
            echo "<p>🛍️ Exporting products...</p>\n";
            $products = DB::table('products')->get();
            foreach ($products as $product) {
                $exportSql .= "INSERT INTO products (id, category_id, name, slug, description, price, stock, image, images, specifications, is_featured, is_active, views, created_at, updated_at) VALUES (";
                $exportSql .= $product->id . ", ";
                $exportSql .= $product->category_id . ", ";
                $exportSql .= "'" . addslashes($product->name) . "', ";
                $exportSql .= "'" . addslashes($product->slug) . "', ";
                $exportSql .= "'" . addslashes($product->description) . "', ";
                $exportSql .= $product->price . ", ";
                $exportSql .= $product->stock . ", ";
                $exportSql .= "'" . addslashes($product->image) . "', ";
                $exportSql .= ($product->images ? "'" . addslashes($product->images) . "'" : "NULL") . ", ";
                $exportSql .= ($product->specifications ? "'" . addslashes($product->specifications) . "'" : "NULL") . ", ";
                $exportSql .= $product->is_featured . ", ";
                $exportSql .= $product->is_active . ", ";
                $exportSql .= $product->views . ", ";
                $exportSql .= "'" . $product->created_at . "', ";
                $exportSql .= "'" . $product->updated_at . "'";
                $exportSql .= ");\n";
            }
            echo "<p>✅ Products exported: {$products->count()}</p>\n";
        }

        echo "<h2>3. Export Results</h2>\n";
        echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px;'>";
        echo "<h3>📋 Copy this SQL content:</h3>";
        echo "<textarea readonly style='width: 100%; height: 400px; font-family: monospace; font-size: 11px; padding: 10px;'>";
        echo htmlspecialchars($exportSql);
        echo "</textarea>";
        echo "</div>";

        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>✅ Export Complete!</h4>";
        echo "<p>📊 <strong>" . substr_count($exportSql, 'INSERT INTO') . " statements</strong> generated</p>";
        echo "<p>📝 Copy the SQL content above and use it in your AWS import script</p>";
        echo "</div>";

    } else {
        echo "<h2>2. Export Options</h2>\n";
        echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
        echo "<h3>⚠️ Important Notes:</h3>";
        echo "<ul>";
        echo "<li>Export <strong>Users first</strong> to enable login functionality</li>";
        echo "<li>Export <strong>Categories</strong> before products (foreign key dependency)</li>";
        echo "<li>Export <strong>Products</strong> to get all your real product data</li>";
        echo "<li>Or export <strong>All</strong> for complete database sync</li>";
        echo "</ul>";
        echo "</div>";

        echo "<form method='post' style='margin: 20px 0;'>\n";
        echo "<h3>📤 Choose Export Type:</h3>\n";
        echo "<div style='margin: 15px 0;'>";
        echo "<label style='display: block; margin: 10px 0;'>";
        echo "<input type='radio' name='export_type' value='users' style='margin-right: 10px;'>";
        echo "<strong>👥 Users Only</strong> - For login functionality";
        echo "</label>";
        echo "<label style='display: block; margin: 10px 0;'>";
        echo "<input type='radio' name='export_type' value='categories' style='margin-right: 10px;'>";
        echo "<strong>📂 Categories Only</strong> - Category structure";
        echo "</label>";
        echo "<label style='display: block; margin: 10px 0;'>";
        echo "<input type='radio' name='export_type' value='products' style='margin-right: 10px;'>";
        echo "<strong>🛍️ Products Only</strong> - All {$products} products";
        echo "</label>";
        echo "<label style='display: block; margin: 10px 0;'>";
        echo "<input type='radio' name='export_type' value='all' checked style='margin-right: 10px;'>";
        echo "<strong>🎯 Complete Export</strong> - Users + Categories + Products (Recommended)";
        echo "</label>";
        echo "</div>";
        echo "<button type='submit' style='background: #28a745; color: white; padding: 15px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;'>📤 Export Data</button>\n";
        echo "</form>\n";

        echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>🔄 Workflow:</h4>";
        echo "<ol>";
        echo "<li><strong>Step 1:</strong> Export data here</li>";
        echo "<li><strong>Step 2:</strong> Copy the generated SQL</li>";
        echo "<li><strong>Step 3:</strong> Use aws-import-json-fixed.php on AWS</li>";
        echo "<li><strong>Step 4:</strong> Paste SQL and import</li>";
        echo "<li><strong>Step 5:</strong> Test login and browse products</li>";
        echo "</ol>";
        echo "</div>";
    }

} catch (Exception $e) {
    echo "<h2>❌ Error:</h2>\n";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>\n";
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

    button:hover {
        opacity: 0.9;
    }

    textarea {
        border: 2px solid #ddd;
        border-radius: 5px;
    }

    textarea:focus {
        border-color: #007bff;
        outline: none;
    }

    label {
        cursor: pointer;
    }

    input[type="radio"] {
        cursor: pointer;
    }
</style>