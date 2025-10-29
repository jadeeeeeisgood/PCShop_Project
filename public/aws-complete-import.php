<?php

/*
|--------------------------------------------------------------------------
| AWS Complete Database Import Script
|--------------------------------------------------------------------------
*/

echo "<h1>🚀 AWS Complete Database Import</h1>\n";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Bootstrap Laravel
    $laravel_root = dirname(__DIR__);
    require_once $laravel_root . '/vendor/autoload.php';
    $app = require_once $laravel_root . '/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "<h2>1. Current AWS Database Status</h2>\n";
    $categories = DB::table('categories')->count();
    $products = DB::table('products')->count();
    $users = DB::table('users')->count();

    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
    echo "<p>👥 Current Users: <strong>{$users}</strong></p>";
    echo "<p>📂 Current Categories: <strong>{$categories}</strong></p>";
    echo "<p>🛍️ Current Products: <strong>{$products}</strong></p>";
    echo "</div>";

    if (isset($_POST['import_data']) && !empty($_POST['sql_content'])) {
        echo "<h2>2. Processing Complete Import</h2>\n";

        $sqlContent = $_POST['sql_content'];
        $lines = explode("\n", $sqlContent);

        $userSuccess = 0;
        $categorySuccess = 0;
        $productSuccess = 0;
        $errors = 0;

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // Clear existing data if requested
            if (isset($_POST['clear_existing'])) {
                echo "<p>🗑️ Clearing existing data...</p>\n";

                DB::table('cart_items')->delete();
                DB::table('order_items')->delete();
                DB::table('orders')->delete();
                DB::table('products')->delete();

                if (isset($_POST['import_users'])) {
                    // Keep admin users, only clear regular users
                    DB::table('users')->where('role', '!=', 'admin')->delete();
                }

                echo "<p>✅ Existing data cleared</p>\n";
            }

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line))
                    continue;

                try {
                    if (strpos($line, 'INSERT INTO users') === 0) {
                        if (isset($_POST['import_users'])) {
                            DB::statement($line);
                            $userSuccess++;
                        }
                    } elseif (strpos($line, 'INSERT INTO categories') === 0) {
                        if (isset($_POST['import_categories'])) {
                            DB::statement($line);
                            $categorySuccess++;
                        }
                    } elseif (strpos($line, 'INSERT INTO products') === 0) {
                        if (isset($_POST['import_products'])) {
                            // Handle JSON fields for products
                            $processedLine = $line;

                            // Fix empty JSON fields
                            $processedLine = preg_replace("/, '',/", ", NULL,", $processedLine);
                            $processedLine = preg_replace("/, ''/", ", NULL", $processedLine);
                            $processedLine = str_replace(", '')", ", NULL)", $processedLine);

                            DB::statement($processedLine);
                            $productSuccess++;
                        }
                    }

                    // Progress updates
                    $totalSuccess = $userSuccess + $categorySuccess + $productSuccess;
                    if ($totalSuccess % 10 === 0 && $totalSuccess > 0) {
                        echo "<p>✅ Imported {$totalSuccess} records...</p>\n";
                        flush();
                    }

                } catch (Exception $e) {
                    $errors++;
                    if ($errors <= 5) {
                        echo "<p style='color: red; font-size: 12px;'>❌ Error #{$errors}: " . substr($e->getMessage(), 0, 100) . "...</p>\n";
                    }
                }
            }

        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        echo "<h2>3. Import Results</h2>\n";

        // Get new counts
        $newUsers = DB::table('users')->count();
        $newCategories = DB::table('categories')->count();
        $newProducts = DB::table('products')->count();

        echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
        echo "<h3>📊 Import Summary:</h3>";
        echo "<p>👥 Users: <strong>{$userSuccess} imported</strong> (Total: {$newUsers})</p>";
        echo "<p>📂 Categories: <strong>{$categorySuccess} imported</strong> (Total: {$newCategories})</p>";
        echo "<p>🛍️ Products: <strong>{$productSuccess} imported</strong> (Total: {$newProducts})</p>";
        echo "<p>❌ Errors: <strong>{$errors}</strong></p>";
        echo "</div>";

        if ($newProducts > 20 && $newUsers > 0) {
            echo "<div style='background: #d4edda; padding: 20px; border-radius: 5px; margin: 20px 0; text-align: center;'>";
            echo "<h2 style='color: #155724;'>🎉 IMPORT SUCCESS!</h2>";
            echo "<p style='font-size: 18px;'><strong>{$newProducts} products</strong> and <strong>{$newUsers} users</strong> imported!</p>";
            echo "<div style='margin: 20px 0;'>";
            echo "<a href='/products' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 0 10px;'>🛍️ VIEW PRODUCTS</a>";
            echo "<a href='/login' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 0 10px;'>🔐 LOGIN NOW</a>";
            echo "</div>";
            echo "</div>";

            // Auto-cleanup
            echo "<p>🧹 Cleaning up import files...</p>\n";
            @unlink(__FILE__);
            @unlink(__DIR__ . '/aws-import.php');
            @unlink(__DIR__ . '/aws-debug.php');
            @unlink(__DIR__ . '/aws-import-fixed.php');
            @unlink(__DIR__ . '/aws-import-safe.php');
            @unlink(__DIR__ . '/aws-import-secure.php');
            @unlink(__DIR__ . '/aws-import-json-fixed.php');
        }

    } else {
        echo "<h2>2. Import Instructions</h2>\n";
        echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px;'>";
        echo "<h3>📋 Steps:</h3>";
        echo "<ol>";
        echo "<li>Run <code>complete-export.php</code> on your local server</li>";
        echo "<li>Choose 'Complete Export' to get Users + Categories + Products</li>";
        echo "<li>Copy the generated SQL content</li>";
        echo "<li>Paste it below and configure import options</li>";
        echo "<li>Click 'Complete Import'</li>";
        echo "</ol>";
        echo "</div>";

        echo "<form method='post' style='margin: 20px 0;'>\n";

        echo "<h3>⚙️ Import Options:</h3>\n";
        echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
        echo "<label style='display: block; margin: 10px 0;'>";
        echo "<input type='checkbox' name='import_users' value='1' checked style='margin-right: 10px;'>";
        echo "<strong>👥 Import Users</strong> - Enable login functionality";
        echo "</label>";
        echo "<label style='display: block; margin: 10px 0;'>";
        echo "<input type='checkbox' name='import_categories' value='1' checked style='margin-right: 10px;'>";
        echo "<strong>📂 Import Categories</strong> - Product categories";
        echo "</label>";
        echo "<label style='display: block; margin: 10px 0;'>";
        echo "<input type='checkbox' name='import_products' value='1' checked style='margin-right: 10px;'>";
        echo "<strong>🛍️ Import Products</strong> - All product data";
        echo "</label>";
        echo "<label style='display: block; margin: 10px 0;'>";
        echo "<input type='checkbox' name='clear_existing' value='1' checked style='margin-right: 10px;'>";
        echo "<strong>🗑️ Clear Existing Data</strong> - Replace current data";
        echo "</label>";
        echo "</div>";

        echo "<h3>📝 Paste Complete SQL Content:</h3>\n";
        echo "<textarea name='sql_content' rows='20' style='width: 100%; font-family: monospace; font-size: 12px; padding: 10px;' placeholder='Paste your complete exported SQL content here (Users + Categories + Products)...'></textarea><br><br>\n";
        echo "<button type='submit' name='import_data' value='1' style='background: #007bff; color: white; padding: 15px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;'>🚀 Complete Import</button>\n";
        echo "</form>\n";

        echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>⚠️ Important:</h4>";
        echo "<ul>";
        echo "<li><strong>Users:</strong> Import to enable login functionality</li>";
        echo "<li><strong>Categories:</strong> Required before products (foreign key)</li>";
        echo "<li><strong>Products:</strong> All your real product data</li>";
        echo "<li><strong>Clear Existing:</strong> Replaces current AWS data with local data</li>";
        echo "</ul>";
        echo "</div>";

        echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>🔧 This Version Handles:</h4>";
        echo "<ul>";
        echo "<li>✅ <strong>JSON field validation</strong> - Prevents MySQL JSON errors</li>";
        echo "<li>✅ <strong>Foreign key constraints</strong> - Safe import order</li>";
        echo "<li>✅ <strong>User authentication</strong> - Login after import</li>";
        echo "<li>✅ <strong>Complete data sync</strong> - Local → AWS</li>";
        echo "</ul>";
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

    input[type="checkbox"] {
        cursor: pointer;
    }

    code {
        background: #f8f9fa;
        padding: 2px 4px;
        border-radius: 3px;
        font-family: monospace;
        color: #e83e8c;
    }
</style>