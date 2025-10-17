@extends('layouts.app')

@section('title', 'Thanh toán - PC Shop')

@section('content')
    <!-- Breadcrumb Section -->
    <section class="bg-gray-50 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-600" aria-label="Breadcrumb">
                <a href="{{ route('welcome') }}" class="hover:text-blue-600 transition-colors whitespace-nowrap">Trang
                    chủ</a>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('cart.index') }}" class="hover:text-blue-600 transition-colors whitespace-nowrap">Giỏ
                    hàng</a>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900 font-medium whitespace-nowrap">Thanh toán</span>
            </nav>
        </div>
    </section>

    <!-- Checkout Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Progress Steps -->
            <div class="mb-12">
                <div class="flex items-center justify-center space-x-8">
                    <div class="flex items-center text-gray-400">
                        <div
                            class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                            ✓
                        </div>
                        <span class="ml-2 text-sm font-medium text-blue-600">Giỏ hàng</span>
                    </div>

                    <div class="w-16 h-0.5 bg-blue-600"></div>

                    <div class="flex items-center text-blue-600">
                        <div
                            class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                            2
                        </div>
                        <span class="ml-2 text-sm font-medium">Thanh toán</span>
                    </div>

                    <div class="w-16 h-0.5 bg-gray-300"></div>

                    <div class="flex items-center text-gray-400">
                        <div
                            class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 text-sm font-medium">
                            3
                        </div>
                        <span class="ml-2 text-sm font-medium">Hoàn thành</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Checkout Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Thông tin giao hàng</h2>
                        </div>

                        <form action="{{ route('checkout.store') }}" method="POST" class="p-6 space-y-6">
                            @csrf

                            <!-- Customer Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Họ và tên <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="customer_name" id="customer_name"
                                        value="{{ auth()->user()->name ?? old('customer_name') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Nhập họ và tên" required>
                                    @error('customer_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Số điện thoại <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="customer_phone" id="customer_phone"
                                        value="{{ old('customer_phone') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Nhập số điện thoại" required>
                                    @error('customer_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="customer_email" id="customer_email"
                                    value="{{ auth()->user()->email ?? old('customer_email') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Nhập địa chỉ email" required>
                                @error('customer_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Địa chỉ giao hàng <span class="text-red-500">*</span>
                                </label>
                                <textarea name="customer_address" id="customer_address" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Nhập địa chỉ giao hàng đầy đủ (số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố)"
                                    required>{{ old('customer_address') }}</textarea>
                                @error('customer_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-4">
                                    Phương thức thanh toán <span class="text-red-500">*</span>
                                </label>
                                <div class="space-y-4">
                                    <label
                                        class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="payment_method" value="cod"
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                                        <div class="ml-3 flex items-center">
                                            <div
                                                class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                                💰
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">Thanh toán khi nhận hàng
                                                    (COD)</div>
                                                <div class="text-sm text-gray-500">Thanh toán bằng tiền mặt khi nhận hàng
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <label
                                        class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="payment_method" value="vnpay"
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <div class="ml-3 flex items-center">
                                            <div
                                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                🏦
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">VNPay</div>
                                                <div class="text-sm text-gray-500">Thanh toán qua thẻ ATM, Visa, MasterCard
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <label
                                        class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="payment_method" value="paypal"
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <div class="ml-3 flex items-center">
                                            <div
                                                class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                                🅿️
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">PayPal</div>
                                                <div class="text-sm text-gray-500">Thanh toán an toàn qua PayPal</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @error('payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Order Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ghi chú đơn hàng (tùy chọn)
                                </label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Ghi chú thêm về đơn hàng (thời gian giao hàng, yêu cầu đặc biệt...)">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-6 border-t border-gray-200">
                                <button type="submit"
                                    class="w-full bg-blue-600 text-white py-4 rounded-xl font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Đặt hàng ngay
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-2xl p-6 shadow-sm border border-gray-200 lg:sticky lg:top-24">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Tóm tắt đơn hàng</h2>

                        <!-- Cart Items -->
                        <div class="space-y-4 mb-6">
                            @foreach($cartItems as $item)
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 relative">
                                        @if($item->product->image)
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}"
                                                class="w-12 h-12 object-cover rounded-lg">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <span
                                            class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                            {{ $item->quantity }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 truncate">
                                            {{ $item->product->name }}
                                        </h4>
                                        <p class="text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}đ
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pricing Summary -->
                        <div class="border-t border-gray-200 pt-6 space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tạm tính ({{ $cartItems->count() }} sản phẩm):</span>
                                <span class="font-medium">{{ number_format($total, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Phí vận chuyển:</span>
                                <span class="font-medium">{{ number_format(30000, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Thuế VAT (10%):</span>
                                <span class="font-medium">{{ number_format($total * 0.1, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-semibold text-gray-900">Tổng cộng:</span>
                                    <span
                                        class="text-xl font-bold text-blue-600">{{ number_format($total * 1.1 + 30000, 0, ',', '.') }}đ</span>
                                </div>
                            </div>
                        </div>

                        <!-- Security Info -->
                        <div class="mt-6 p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-green-800">Giao dịch bảo mật</p>
                                    <p class="text-xs text-green-600">Thông tin của bạn được mã hóa an toàn</p>
                                </div>
                            </div>
                        </div>

                        <!-- Return Policy -->
                        <div class="mt-4 text-center">
                            <a href="{{ route('cart.index') }}"
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                ← Quay lại giỏ hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection