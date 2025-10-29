<?php

/*
|--------------------------------------------------------------------------
| Smart Database Export Script
|--------------------------------------------------------------------------
*/

echo "<h1>🧠 Smart Database Export</h1>\n";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Bootstrap Laravel
    $laravel_root = dirname(__DIR__);
    require_once $laravel_root . '/vendor/autoload.php';
    $app = require_once $laravel_root . '/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "<h2>1. Database Connection</h2>\n";
    $dbConfig = config('database.connections.mysql');
    echo "<p><strong>Database:</strong> {$dbConfig['database']} @ {$dbConfig['host']}</p>\n";

    echo "<h2>2. Data Analysis</h2>\n";

    // Get actual table structures
    $userColumns = collect(DB::select("DESCRIBE users"))->pluck('Field')->toArray();
    $categoryColumns = collect(DB::select("DESCRIBE categories"))->pluck('Field')->toArray();
    $productColumns = collect(DB::select("DESCRIBE products"))->pluck('Field')->toArray();

    $users = DB::table('users')->count();
    $categories = DB::table('categories')->count();
    $products = DB::table('products')->count();

    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
    echo "<p>👥 Users: <strong>{$users}</strong> (Columns: " . implode(', ', $userColumns) . ")</p>";
    echo "<p>📂 Categories: <strong>{$categories}</strong> (Columns: " . implode(', ', $categoryColumns) . ")</p>";
    echo "<p>🛍️ Products: <strong>{$products}</strong> (Columns: " . implode(', ', $productColumns) . ")</p>";
    echo "</div>";

    if (isset($_POST['export_type'])) {
        $exportType = $_POST['export_type'];

        echo "<h2>3. Exporting Data ({$exportType})</h2>\n";

        $exportSql = "";
        $exportSql .= "-- Smart Database Export\n";
        $exportSql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $exportSql .= "-- Database: {$dbConfig['database']}\n\n";

        if ($exportType === 'users' || $exportType === 'all') {
            echo "<p>👥 Exporting users...</p>\n";
            $usersData = DB::table('users')->get();

            foreach ($usersData as $user) {
                $exportSql .= "INSERT INTO users (";
                $exportSql .= implode(', ', $userColumns);
                $exportSql .= ") VALUES (";

                $values = [];
                foreach ($userColumns as $column) {
                    $value = $user->$column ?? null;
                    if (is_null($value)) {
                        $values[] = "NULL";
                    } elseif (is_numeric($value)) {
                        $values[] = $value;
                    } else {
                        $values[] = "'" . addslashes($value) . "'";
                    }
                }

                $exportSql .= implode(', ', $values);
                $exportSql .= ");\n";
            }
            echo "<p>✅ Users exported: {$usersData->count()}</p>\n";
        }

        if ($exportType === 'categories' || $exportType === 'all') {
            echo "<p>📂 Exporting categories...</p>\n";
            $categoriesData = DB::table('categories')->get();

            foreach ($categoriesData as $category) {
                $exportSql .= "INSERT INTO categories (";
                $exportSql .= implode(', ', $categoryColumns);
                $exportSql .= ") VALUES (";

                $values = [];
                foreach ($categoryColumns as $column) {
                    $value = $category->$column ?? null;
                    if (is_null($value)) {
                        $values[] = "NULL";
                    } elseif (is_numeric($value)) {
                        $values[] = $value;
                    } else {
                        $values[] = "'" . addslashes($value) . "'";
                    }
                }

                $exportSql .= implode(', ', $values);
                $exportSql .= ");\n";
            }
            echo "<p>✅ Categories exported: {$categoriesData->count()}</p>\n";
        }

        if ($exportType === 'products' || $exportType === 'all') {
            echo "<p>🛍️ Exporting products...</p>\n";
            $productsData = DB::table('products')->get();

            foreach ($productsData as $product) {
                $exportSql .= "INSERT INTO products (";
                $exportSql .= implode(', ', $productColumns);
                $exportSql .= ") VALUES (";

                $values = [];
                foreach ($productColumns as $column) {
                    $value = $product->$column ?? null;
                    if (is_null($value)) {
                        $values[] = "NULL";
                    } elseif (is_numeric($value) && !in_array($column, ['slug', 'image', 'images', 'specifications'])) {
                        $values[] = $value;
                    } else {
                        $values[] = "'" . addslashes($value) . "'";
                    }
                }

                $exportSql .= implode(', ', $values);
                $exportSql .= ");\n";
            }
            echo "<p>✅ Products exported: {$productsData->count()}</p>\n";
        }

        echo "<h2>4. Export Results</h2>\n";

        // Save to file
        $filename = "smart_export_{$exportType}_" . date('Ymd_His') . ".sql";
        $filepath = __DIR__ . '/' . $filename;
        file_put_contents($filepath, $exportSql);

        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";
        echo "<h4>✅ Export Complete!</h4>";
        echo "<p>📄 File: <a href='/{$filename}' download style='font-weight: bold;'>{$filename}</a></p>";
        echo "<p>📊 Size: " . number_format(filesize($filepath)) . " bytes</p>";
        echo "<p>📝 Statements: " . substr_count($exportSql, 'INSERT INTO') . "</p>";
        echo "</div>";

        echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>📋 SQL Preview (first 1000 chars):</h4>";
        echo "<textarea readonly style='width: 100%; height: 200px; font-family: monospace; font-size: 11px;'>";
        echo htmlspecialchars(substr($exportSql, 0, 1000));
        if (strlen($exportSql) > 1000) {
            echo "\n\n... (truncated, see full file download)";
        }
        echo "</textarea>";
        echo "</div>";

    } else {
        echo "<h2>3. Export Options</h2>\n";

        if ($products < 5) {
            echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h4>⚠️ Low Product Count Warning</h4>";
            echo "<p>Only <strong>{$products} products</strong> detected. This might indicate:</p>";
            echo "<ul>";
            echo "<li>🔄 Database not fully seeded</li>";
            echo "<li>🗄️ Connected to wrong database</li>";
            echo "<li>📊 Data not migrated properly</li>";
            echo "</ul>";
            echo "<p><strong>Check:</strong> <a href='/database-debug.php'>Database Debug Tool</a></p>";
            echo "</div>";
        }

        echo "<form method='post' style='margin: 20px 0;'>\n";
        echo "<h3>📤 Choose Export Type:</h3>\n";
        echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;'>";

        echo "<label style='display: block; margin: 10px 0; cursor: pointer;'>";
        echo "<input type='radio' name='export_type' value='users' style='margin-right: 10px;'>";
        echo "<strong>👥 Users Only</strong> ({$users} records) - For login functionality";
        echo "</label>";

        echo "<label style='display: block; margin: 10px 0; cursor: pointer;'>";
        echo "<input type='radio' name='export_type' value='categories' style='margin-right: 10px;'>";
        echo "<strong>📂 Categories Only</strong> ({$categories} records) - Category structure";
        echo "</label>";

        echo "<label style='display: block; margin: 10px 0; cursor: pointer;'>";
        echo "<input type='radio' name='export_type' value='products' style='margin-right: 10px;'>";
        echo "<strong>🛍️ Products Only</strong> ({$products} records) - Product catalog";
        echo "</label>";

        echo "<label style='display: block; margin: 10px 0; cursor: pointer;'>";
        echo "<input type='radio' name='export_type' value='all' checked style='margin-right: 10px;'>";
        $totalRecords = $users + $categories + $products;
        echo "<strong>🎯 Complete Export</strong> (All {$totalRecords} records) - Recommended";
        echo "</label>";

        echo "</div>";
        echo "<button type='submit' style='background: #28a745; color: white; padding: 15px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;'>📤 Smart Export</button>\n";
        echo "</form>\n";

        echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>🧠 Smart Features:</h4>";
        echo "<ul>";
        echo "<li>✅ <strong>Dynamic column detection</strong> - Adapts to your table structure</li>";
        echo "<li>✅ <strong>Safe data handling</strong> - Proper escaping and null handling</li>";
        echo "<li>✅ <strong>Type awareness</strong> - Numeric vs string field detection</li>";
        echo "<li>✅ <strong>Error prevention</strong> - Handles missing columns gracefully</li>";
        echo "</ul>";
        echo "</div>";
    }

} catch (Exception $e) {
    echo "<h2>❌ Error:</h2>\n";
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
    echo "<p style='color: #721c24; font-weight: bold;'>" . $e->getMessage() . "</p>";
    echo "<pre style='color: #721c24; font-size: 12px;'>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
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

    label {
        cursor: pointer;
    }

    input[type="radio"] {
        cursor: pointer;
    }

    a {
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>