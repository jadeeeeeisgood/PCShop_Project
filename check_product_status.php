<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PRODUCT ACTIVITY STATUS ===" . PHP_EOL;
$activeCount = App\Models\Product::where('is_active', true)->count();
$inactiveCount = App\Models\Product::where('is_active', false)->count();
$totalCount = App\Models\Product::count();

echo "Total Products: " . $totalCount . PHP_EOL;
echo "Active Products: " . $activeCount . PHP_EOL;
echo "Inactive Products: " . $inactiveCount . PHP_EOL;
echo PHP_EOL;

echo "=== SAMPLE PRODUCTS STATUS ===" . PHP_EOL;
$products = App\Models\Product::take(10)->get();
foreach ($products as $product) {
    echo sprintf(
        "ID: %d | %s | is_active: %s | stock: %d\n",
        $product->id,
        substr($product->name, 0, 30),
        $product->is_active ? 'true' : 'false',
        $product->stock
    );
}

echo PHP_EOL;
echo "=== TESTING FRONTEND QUERY ===" . PHP_EOL;
$frontendProducts = App\Models\Product::with('category')->where('is_active', true)->count();
echo "Products visible on frontend: " . $frontendProducts . PHP_EOL;

?>