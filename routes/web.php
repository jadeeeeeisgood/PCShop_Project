<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->except(['create', 'store', 'destroy']);
    Route::get('orders/{order}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'exportInvoice'])->name('orders.invoice');
    Route::post('orders/bulk-update', [\App\Http\Controllers\Admin\OrderController::class, 'bulkUpdate'])->name('orders.bulk-update');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('users/bulk-action', [\App\Http\Controllers\Admin\UserController::class, 'bulkAction'])->name('users.bulk-action');
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
    Route::get('/checkout/success/{order?}', [CheckoutController::class, 'success'])->name('checkout.success');

    // VNPay payment routes
    Route::post('/payment/vnpay/{order}', [\App\Http\Controllers\VNPayController::class, 'createPayment'])->name('payment.vnpay');
});

// VNPay callback (no auth required)
Route::get('/payment/vnpay/callback', [\App\Http\Controllers\VNPayController::class, 'callback'])->name('payment.vnpay.callback');

Route::get('/order-tracking', [CheckoutController::class, 'orderTracking'])->name('order.tracking');



// Demo real-time features
Route::get('/demo/real-time-features', function () {
    return view('demo.real-time-features');
})->name('demo.real-time-features');



require __DIR__ . '/auth.php';
