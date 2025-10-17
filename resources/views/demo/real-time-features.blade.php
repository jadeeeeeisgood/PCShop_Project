@extends('layouts.app')

@section('title', 'Demo Real-time Features - PC Shop')

@section('content')
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-16">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Demo Real-time Features</h1>
                <p class="text-xl text-gray-600">Xem demo các tính năng thời gian thực và thanh toán VNPay</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Real-time Stock Updates Demo -->
                <div class="bg-gray-50 rounded-2xl p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            📊
                        </div>
                        Real-time Stock Updates
                    </h2>

                    <div class="space-y-6">
                        <!-- Sample Product Cards -->
                        @php
                            $sampleProducts = [
                                ['id' => 1, 'name' => 'MacBook Pro M3', 'stock' => 5, 'price' => 35000000],
                                ['id' => 2, 'name' => 'iPhone 15 Pro', 'stock' => 12, 'price' => 28000000],
                                ['id' => 3, 'name' => 'Samsung Galaxy S24', 'stock' => 3, 'price' => 22000000]
                            ];
                        @endphp

                        @foreach($sampleProducts as $product)
                            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200"
                                data-product-id="{{ $product['id'] }}">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $product['name'] }}</h3>
                                        <p class="text-blue-600 font-bold">{{ number_format($product['price'], 0, ',', '.') }}đ
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span data-stock-display="{{ $product['id'] }}"
                                            class="{{ $product['stock'] <= 5 ? 'text-warning' : 'text-success' }} small">
                                            Còn lại: {{ $product['stock'] }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <input type="number" value="1" min="1" max="{{ $product['stock'] }}"
                                            class="w-16 px-2 py-1 border border-gray-300 rounded text-center text-sm">
                                        <button data-add-to-cart data-product-id="{{ $product['id'] }}"
                                            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                            Thêm vào giỏ
                                        </button>
                                    </div>

                                    <div class="flex gap-2">
                                        <button onclick="simulateStockUpdate({{ $product['id'] }}, -1)"
                                            class="bg-red-100 text-red-600 px-3 py-1 rounded text-sm hover:bg-red-200 transition-colors">
                                            -1 Stock
                                        </button>
                                        <button onclick="simulateStockUpdate({{ $product['id'] }}, 1)"
                                            class="bg-green-100 text-green-600 px-3 py-1 rounded text-sm hover:bg-green-200 transition-colors">
                                            +1 Stock
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Stock Update Simulation Controls -->
                        <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                            <h4 class="font-semibold text-blue-900 mb-3">Simulation Controls</h4>
                            <div class="space-y-3">
                                <button onclick="simulateMultipleUpdates()"
                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                    Simulate Multiple Stock Updates
                                </button>
                                <button onclick="simulateLowStock()"
                                    class="w-full bg-orange-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-orange-700 transition-colors">
                                    Simulate Low Stock Alert
                                </button>
                                <button onclick="simulateOutOfStock()"
                                    class="w-full bg-red-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-red-700 transition-colors">
                                    Simulate Out of Stock
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- VNPay Payment Demo -->
                <div class="bg-gray-50 rounded-2xl p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            💳
                        </div>
                        VNPay Payment Integration
                    </h2>

                    <div class="space-y-6">
                        <!-- Sample Order -->
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                            <h3 class="font-semibold text-gray-900 mb-4">Sample Order #12345</h3>

                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">MacBook Pro M3 x1</span>
                                    <span class="font-medium">35.000.000đ</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">iPhone 15 Pro x1</span>
                                    <span class="font-medium">28.000.000đ</span>
                                </div>
                                <div class="border-t pt-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Tạm tính:</span>
                                        <span class="font-medium">63.000.000đ</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Phí vận chuyển:</span>
                                        <span class="font-medium">50.000đ</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">VAT (10%):</span>
                                        <span class="font-medium">6.300.000đ</span>
                                    </div>
                                    <div class="flex justify-between font-bold text-lg border-t pt-2 mt-2">
                                        <span>Tổng cộng:</span>
                                        <span class="text-blue-600">69.350.000đ</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Methods -->
                            <div class="space-y-3 mb-6">
                                <h4 class="font-medium text-gray-900">Phương thức thanh toán:</h4>

                                <label
                                    class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="demo_payment" value="cod" class="mr-3" checked>
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                            💰</div>
                                        <div>
                                            <div class="font-medium">Thanh toán khi nhận hàng (COD)</div>
                                            <div class="text-sm text-gray-500">Thanh toán bằng tiền mặt</div>
                                        </div>
                                    </div>
                                </label>

                                <label
                                    class="flex items-center p-3 border border-blue-300 rounded-lg cursor-pointer hover:bg-blue-50 bg-blue-25">
                                    <input type="radio" name="demo_payment" value="vnpay" class="mr-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            🏦</div>
                                        <div>
                                            <div class="font-medium text-blue-900">VNPay</div>
                                            <div class="text-sm text-blue-600">Thanh toán qua thẻ ATM, Visa, MasterCard
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-3">
                                <button onclick="demoVNPayPayment()"
                                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                    Demo VNPay Payment
                                </button>
                                <button onclick="demoCODPayment()"
                                    class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                                    Demo COD Order
                                </button>
                            </div>
                        </div>

                        <!-- VNPay Features -->
                        <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                            <h4 class="font-semibold text-blue-900 mb-3">VNPay Features</h4>
                            <ul class="space-y-2 text-sm text-blue-800">
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Secure payment processing
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Multiple payment methods
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Real-time payment confirmation
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Automatic stock management
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical Information -->
            <div class="mt-16 bg-gray-900 rounded-2xl p-8 text-white">
                <h2 class="text-2xl font-bold mb-6 flex items-center gap-3">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        ⚙️
                    </div>
                    Technical Implementation
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-xl font-semibold mb-4 text-blue-400">Real-time Stock Updates</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li>• Pusher WebSocket integration</li>
                            <li>• Laravel Broadcasting events</li>
                            <li>• Stock reservation system</li>
                            <li>• Automatic UI updates</li>
                            <li>• Low stock notifications</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold mb-4 text-green-400">VNPay Integration</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li>• Secure payment gateway</li>
                            <li>• HMAC signature verification</li>
                            <li>• Transaction tracking</li>
                            <li>• Automated order processing</li>
                            <li>• Payment confirmation callbacks</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Demo functions for stock updates
        function simulateStockUpdate(productId, change) {
            // This would normally be triggered by real stock changes
            // For demo purposes, we'll manually trigger the stock manager
            if (window.stockManager) {
                const currentStock = Math.max(0, Math.floor(Math.random() * 20) + change);
                window.stockManager.handleStockUpdate({
                    product_id: productId,
                    available_stock: currentStock
                });
            }
        }

        function simulateMultipleUpdates() {
            const products = [1, 2, 3];
            products.forEach((productId, index) => {
                setTimeout(() => {
                    simulateStockUpdate(productId, Math.floor(Math.random() * 3) - 1);
                }, index * 1000);
            });
        }

        function simulateLowStock() {
            simulateStockUpdate(1, -10); // Force low stock
            setTimeout(() => {
                if (window.stockManager) {
                    window.stockManager.handleStockUpdate({
                        product_id: 1,
                        available_stock: 2
                    });
                }
            }, 500);
        }

        function simulateOutOfStock() {
            if (window.stockManager) {
                window.stockManager.handleStockUpdate({
                    product_id: 2,
                    available_stock: 0
                });
            }
        }

        // Demo functions for payments
        function demoVNPayPayment() {
            alert('Demo: Redirecting to VNPay payment gateway...\n\nIn a real scenario, this would:\n1. Create payment URL\n2. Redirect to VNPay\n3. Process payment\n4. Return with confirmation');
        }

        function demoCODPayment() {
            alert('Demo: COD Order created successfully!\n\nIn a real scenario, this would:\n1. Create order with COD payment\n2. Reserve stock\n3. Send confirmation email\n4. Update order status');
        }

        // Initialize demo data
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Real-time Features Demo Page Loaded');

            // Simulate some initial stock updates after a short delay
            setTimeout(() => {
                console.log('Running initial stock simulation...');
                simulateStockUpdate(1, 0);
                simulateStockUpdate(2, 0);
                simulateStockUpdate(3, 0);
            }, 2000);
        });
    </script>
@endsection