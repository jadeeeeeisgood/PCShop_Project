<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Test route to verify callback works without auth
Route::match(['GET', 'POST'], '/callback-test', function (Illuminate\Http\Request $request) {
    \Log::info('Callback test route accessed');
    echo "<h1>Callback Test</h1>";
    echo "<p>Method: " . $request->method() . "</p>";
    echo "<p>URL: " . $request->fullUrl() . "</p>";
    echo "<p>Request data: " . json_encode($request->all()) . "</p>";
    echo "<p>Time: " . date('Y-m-d H:i:s') . "</p>";
    return;
});

// VNPay callback - place early to avoid middleware conflicts  
Route::match(['GET', 'POST'], '/payment/vnpay/callback', [\App\Http\Controllers\VNPayController::class, 'callback'])->name('payment.vnpay.callback');

// Test VNPay Controller directly
Route::get('/vnpay-controller-test', function () {
    try {
        echo "<h1>VNPay Controller Test</h1>";
        $controller = new \App\Http\Controllers\VNPayController(
            new \App\Services\VNPayService(),
            new \App\Services\StockService()
        );
        echo "<p>Controller created successfully</p>";
        echo "<p>Methods: " . implode(', ', get_class_methods($controller)) . "</p>";
        return;
    } catch (\Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
        echo "<p>Trace: " . $e->getTraceAsString() . "</p>";
        return;
    }
});

Route::get('/', function () {
    $featuredProducts = \App\Models\Product::where('is_featured', true)
        ->with('category')
        ->latest()
        ->take(6)
        ->get();

    $newProducts = \App\Models\Product::with('category')
        ->latest()
        ->take(6)
        ->get();

    $popularProducts = \App\Models\Product::with('category')
        ->orderBy('views', 'desc')
        ->take(8)
        ->get();

    $categories = \App\Models\Category::withCount('products')
        ->take(8)
        ->get();

    return view('welcome', compact('featuredProducts', 'newProducts', 'popularProducts', 'categories'));
})->name('home');

Route::get('/welcome', function () {
    return redirect()->route('home');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('profile.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User order routes
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/orders/{order}', [ProfileController::class, 'showOrder'])->name('profile.order');
    Route::patch('/profile/orders/{order}/cancel', [ProfileController::class, 'cancelOrder'])->name('profile.order.cancel');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('products', AdminProductController::class);
    Route::post('products/bulk-action', [AdminProductController::class, 'bulkAction'])->name('products.bulk-action');
    Route::delete('products/{product}/remove-image', [AdminProductController::class, 'removeImage'])->name('products.remove-image');
    Route::get('products/{product}/images', [AdminProductController::class, 'manageImages'])->name('products.images');
    Route::post('products/{product}/upload-images', [AdminProductController::class, 'uploadImages'])->name('products.upload-images');
    Route::post('products/{product}/delete-image', [AdminProductController::class, 'deleteImage'])->name('products.delete-image');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->except(['create', 'store']);
    Route::get('orders/{order}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'exportInvoice'])->name('orders.invoice');
    Route::post('orders/bulk-update', [\App\Http\Controllers\Admin\OrderController::class, 'bulkUpdate'])->name('orders.bulk-update');
    Route::delete('orders/bulk-delete', [\App\Http\Controllers\Admin\OrderController::class, 'bulkDelete'])->name('orders.bulk-delete');
    Route::post('orders/{order}/cod-complete', [\App\Http\Controllers\Admin\OrderController::class, 'markCodComplete'])->name('orders.cod-complete');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('users/bulk-action', [\App\Http\Controllers\Admin\UserController::class, 'bulkAction'])->name('users.bulk-action');
    Route::put('users/{user}/change-password', [\App\Http\Controllers\Admin\UserController::class, 'changePassword'])->name('users.change-password');
});

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/items', [CartController::class, 'items'])->name('cart.items');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add')->middleware('web');
Route::post('/cart/add/{product}', [CartController::class, 'addProduct'])->name('cart.add.product')->middleware('web');
Route::get('/cart/test', function () {
    return response()->json(['status' => 'Cart routes working']);
});

Route::patch('/cart/update-multiple', [CartController::class, 'updateMultiple'])->name('cart.update-multiple');
Route::patch('/cart/update-product/{product}', [CartController::class, 'update'])->name('cart.update');
Route::patch('/cart/update-item/{cartItem}', [CartController::class, 'updateItem'])->name('cart.update-item');
Route::delete('/cart/remove-product/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/remove-item/{cartItem}', [CartController::class, 'removeItem'])->name('cart.remove-item');
Route::delete('/cart/remove/{cartItem}', [CartController::class, 'removeItem'])->name('cart.remove.item');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Stock API endpoints
Route::get('/api/stock/{product}', [CartController::class, 'getAvailableStock'])->name('api.stock');
Route::post('/api/stock/reserve', [CartController::class, 'reserveStock'])->name('api.stock.reserve');
Route::post('/api/stock/release', [CartController::class, 'releaseStock'])->name('api.stock.release');

Route::get('/products', [ProductController::class, 'frontendIndex'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'frontendShow'])->name('products.show');
Route::get('/categories/{category}', [ProductController::class, 'frontendByCategory'])->name('products.category');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // VNPay payment routes
    Route::get('/payment/vnpay/{order}', [\App\Http\Controllers\VNPayController::class, 'createPayment'])->name('payment.vnpay');
});

// Success page should be accessible without auth (for redirects)
Route::get('/checkout/success/{order?}', [CheckoutController::class, 'success'])->name('checkout.success');

// Manual VNPay completion (for when callback fails)
Route::get('/vnpay/manual/{order}', function ($orderId) {
    $order = \App\Models\Order::find($orderId);
    if ($order && $order->payment_method === 'vnpay' && $order->payment_status === 'pending') {
        return view('vnpay.manual-completion', compact('order'));
    }
    return redirect()->route('welcome');
})->name('vnpay.manual');

// Test route to verify routing works
Route::get('/test-route', function () {
    return response()->json([
        'message' => 'Routes are working',
        'checkout_success_url' => route('checkout.success', ['order' => 1]),
        'generated_route' => url('/checkout/success/1')
    ]);
});

// Simple test route
Route::get('/test-simple', function () {
    \Log::info('Simple test route hit');
    return "Simple test route works!";
});

// VNPay callback with shorter path
Route::match(['GET', 'POST'], '/vnpay-callback', function (Illuminate\Http\Request $request) {
    \Log::info('VNPay callback route hit', ['data' => $request->all()]);
    echo "<h1>VNPay Callback Working!</h1>";
    echo "<p>TxnRef: " . $request->get('vnp_TxnRef', 'N/A') . "</p>";
    echo "<p>Status: " . $request->get('vnp_TransactionStatus', 'N/A') . "</p>";
    echo "<p>Amount: " . $request->get('vnp_Amount', 'N/A') . "</p>";
    echo "<p>Response Code: " . $request->get('vnp_ResponseCode', 'N/A') . "</p>";
    return;
});

// Test VNPay without auth (temporary)
Route::get('/test/vnpay/{order}', function (\App\Models\Order $order) {
    echo "Order ID: " . $order->id . "<br>";
    echo "Order Total: " . $order->total . "<br>";
    echo "VNPay Config: " . json_encode(config('services.vnpay')) . "<br>";

    $vnpay = new \App\Services\VNPayService();
    try {
        $url = $vnpay->createPaymentUrl($order, request()->ip());
        echo "VNPay URL: " . $url . "<br>";
        echo '<a href="' . $url . '">Click to pay</a>';
        return;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return;
    }
})->name('test.vnpay');

Route::get('/order-tracking', [CheckoutController::class, 'orderTracking'])->name('order.tracking');



// Demo real-time features
Route::get('/demo/real-time-features', function () {
    return view('demo.real-time-features');
})->name('demo.real-time-features');



require __DIR__ . '/auth.php';
