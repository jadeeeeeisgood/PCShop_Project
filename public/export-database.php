<?php

/*
|--------------------------------------------------------------------------
| Database Export Script
|--------------------------------------------------------------------------
|
| This script exports current database data to SQL file for AWS import
|
*/

echo "<h1>📦 Database Export for AWS</h1>\n";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Bootstrap Laravel
    $laravel_root = dirname(__DIR__);
    require_once $laravel_root . '/vendor/autoload.php';
    $app = require_once $laravel_root . '/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "<h2>1. Database Info</h2>\n";
    $dbConfig = config('database.connections.mysql');
    echo "<p>Host: " . $dbConfig['host'] . "</p>\n";
    echo "<p>Database: " . $dbConfig['database'] . "</p>\n";

    echo "<h2>2. Current Data Count</h2>\n";
    $categories = DB::table('categories')->count();
    $products = DB::table('products')->count();
    $users = DB::table('users')->count();

    echo "<p>Categories: {$categories}</p>\n";
    echo "<p>Products: {$products}</p>\n";
    echo "<p>Users: {$users}</p>\n";

    echo "<h2>3. Generating SQL Export</h2>\n";

    $sql = "-- PC Shop Database Export\n";
    $sql .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";

    // Export categories
    $sql .= "-- Categories Data\n";
    $sql .= "DELETE FROM categories WHERE id > 0;\n";
    $categoriesData = DB::table('categories')->get();
    foreach ($categoriesData as $cat) {
        $sql .= "INSERT INTO categories (id, name, slug, created_at, updated_at) VALUES (";
        $sql .= "{$cat->id}, ";
        $sql .= "'" . addslashes($cat->name) . "', ";
        $sql .= "'" . addslashes($cat->slug) . "', ";
        $sql .= "'" . $cat->created_at . "', ";
        $sql .= "'" . $cat->updated_at . "');\n";
    }
    $sql .= "\n";

    // Export products
    $sql .= "-- Products Data\n";
    $sql .= "DELETE FROM products WHERE id > 0;\n";
    $productsData = DB::table('products')->get();
    foreach ($productsData as $product) {
        $sql .= "INSERT INTO products (id, category_id, name, slug, description, price, stock, image, images, specifications, is_featured, is_active, views, created_at, updated_at) VALUES (";
        $sql .= "{$product->id}, ";
        $sql .= "{$product->category_id}, ";
        $sql .= "'" . addslashes($product->name) . "', ";
        $sql .= "'" . addslashes($product->slug) . "', ";
        $sql .= "'" . addslashes($product->description ?? '') . "', ";
        $sql .= "{$product->price}, ";
        $sql .= "{$product->stock}, ";
        $sql .= "'" . addslashes($product->image ?? '') . "', ";
        $sql .= "'" . addslashes($product->images ?? '[]') . "', ";
        $sql .= "'" . addslashes($product->specifications ?? '') . "', ";
        $sql .= ($product->is_featured ? 1 : 0) . ", ";
        $sql .= ($product->is_active ? 1 : 0) . ", ";
        $sql .= "{$product->views}, ";
        $sql .= "'" . $product->created_at . "', ";
        $sql .= "'" . $product->updated_at . "');\n";
    }

    // Save to file
    $filename = 'database_export_' . date('Y_m_d_H_i_s') . '.sql';
    $filepath = __DIR__ . '/' . $filename;
    file_put_contents($filepath, $sql);

    echo "<p>✅ SQL Export created: <a href='/{$filename}' download>{$filename}</a></p>\n";
    echo "<p>File size: " . number_format(filesize($filepath)) . " bytes</p>\n";

    echo "<h2>4. AWS Import Instructions</h2>\n";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
    echo "<h3>Method 1: Via AWS Debug Script</h3>";
    echo "<p>1. Download the SQL file above</p>";
    echo "<p>2. Upload it to AWS public folder</p>";
    echo "<p>3. Create import script on AWS to execute the SQL</p>";
    echo "<h3>Method 2: Via AWS Console</h3>";
    echo "<p>1. Access AWS RDS Console</p>";
    echo "<p>2. Connect to database</p>";
    echo "<p>3. Import the SQL file</p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<h2>❌ Error:</h2>\n";
    echo "<p>" . $e->getMessage() . "</p>\n";
}

?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    h1 {
        color: #007bff;
    }

    h2 {
        color: #28a745;
        border-bottom: 2px solid #28a745;
        padding-bottom: 5px;
    }

    div {
        margin: 10px 0;
    }
</style>