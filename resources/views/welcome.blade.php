@extends('layouts.app')

@section('title', 'PC Shop - Cửa hàng máy tính hàng đầu Việt Nam')

@push('styles')
    <style>
        .hero-bg {
            background: linear-gradient(135deg, #E5EAF4 0%, #F3F4F6 100%);
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="overflow-hidden pb-10 lg:pb-12 xl:pb-16 bg-gray-100 hero-bg">
        <div class="max-w-[1170px] w-full mx-auto px-4 sm:px-8 xl:px-0">
            <div class="flex flex-wrap gap-5">
                <!-- Hero Carousel -->
                <div class="xl:max-w-[757px] w-full">
                    <div class="relative z-10 rounded-lg bg-white overflow-hidden shadow-lg">
                        <!-- Swiper -->
                        <div class="swiper hero-carousel">
                            <div class="swiper-wrapper">
                                <!-- Slide 1 -->
                                <div class="swiper-slide">
                                    <div class="flex items-center pt-6 sm:pt-0 flex-col-reverse sm:flex-row">
                                        <div class="max-w-[394px] py-10 sm:py-16 lg:py-20 pl-4 sm:pl-8 lg:pl-12">
                                            <div class="flex items-center gap-4 mb-8 sm:mb-10">
                                                <span
                                                    class="block font-semibold text-3xl sm:text-5xl text-blue-600">30%</span>
                                                <span class="block text-gray-900 text-sm sm:text-lg leading-6">
                                                    Giảm giá<br>
                                                    HOT
                                                </span>
                                            </div>
                                            <h1 class="font-semibold text-gray-900 text-xl sm:text-3xl mb-3">
                                                <a href="{{ route('products.index', ['category' => 1]) }}">
                                                    PC Gaming RTX 4080 Super
                                                </a>
                                            </h1>
                                            <p class="text-gray-600 mb-6">
                                                Trải nghiệm gaming đỉnh cao với bộ PC gaming mạnh mẽ, card đồ họa RTX 4080
                                                Super mới nhất.
                                            </p>
                                            <a href="{{ route('products.index', ['category' => 1]) }}"
                                                class="inline-flex font-medium text-white text-sm rounded-md bg-gray-900 py-3 px-9 hover:bg-blue-600 transition-colors duration-200">
                                                Mua ngay
                                            </a>
                                        </div>
                                        <div class="flex-1 flex justify-center">
                                            <img src="{{ asset('img/hero-pc-1.png') }}" alt="Gaming PC"
                                                class="max-w-full h-auto">
                                        </div>
                                    </div>
                                </div>

                                <!-- Slide 2 -->
                                <div class="swiper-slide">
                                    <div class="flex items-center pt-6 sm:pt-0 flex-col-reverse sm:flex-row">
                                        <div class="max-w-[394px] py-10 sm:py-16 lg:py-20 pl-4 sm:pl-8 lg:pl-12">
                                            <div class="flex items-center gap-4 mb-8 sm:mb-10">
                                                <span
                                                    class="block font-semibold text-3xl sm:text-5xl text-blue-600">25%</span>
                                                <span class="block text-gray-900 text-sm sm:text-lg leading-6">
                                                    Ưu đãi<br>
                                                    Đặc biệt
                                                </span>
                                            </div>
                                            <h1 class="font-semibold text-gray-900 text-xl sm:text-3xl mb-3">
                                                <a href="{{ route('products.index', ['category' => 5]) }}">
                                                    MacBook Pro M3 Max
                                                </a>
                                            </h1>
                                            <p class="text-gray-600 mb-6">
                                                Sức mạnh vượt trội cho công việc chuyên nghiệp với chip M3 Max mới nhất từ
                                                Apple.
                                            </p>
                                            <a href="{{ route('products.index', ['category' => 5]) }}"
                                                class="inline-flex font-medium text-white text-sm rounded-md bg-gray-900 py-3 px-9 hover:bg-blue-600 transition-colors duration-200">
                                                Khám phá
                                            </a>
                                        </div>
                                        <div class="flex-1 flex justify-center">
                                            <img src="{{ asset('img/hero-laptop-1.png') }}" alt="MacBook Pro"
                                                class="max-w-full h-auto">
                                        </div>
                                    </div>
                                </div>

                                <!-- Slide 3 -->
                                <div class="swiper-slide">
                                    <div class="flex items-center pt-6 sm:pt-0 flex-col-reverse sm:flex-row">
                                        <div class="max-w-[394px] py-10 sm:py-16 lg:py-20 pl-4 sm:pl-8 lg:pl-12">
                                            <div class="flex items-center gap-4 mb-8 sm:mb-10">
                                                <span
                                                    class="block font-semibold text-3xl sm:text-5xl text-red-600">HOT</span>
                                                <span class="block text-gray-900 text-sm sm:text-lg leading-6">
                                                    Mới<br>
                                                    Nhất
                                                </span>
                                            </div>
                                            <h1 class="font-semibold text-gray-900 text-xl sm:text-3xl mb-3">
                                                <a href="{{ route('products.index', ['category' => 7]) }}">
                                                    Gaming Gear Premium
                                                </a>
                                            </h1>
                                            <p class="text-gray-600 mb-6">
                                                Bộ phụ kiện gaming cao cấp: chuột, bàn phím, tai nghe từ các thương hiệu
                                                hàng đầu.
                                            </p>
                                            <a href="{{ route('products.index', ['category' => 7]) }}"
                                                class="inline-flex font-medium text-white text-sm rounded-md bg-gray-900 py-3 px-9 hover:bg-blue-600 transition-colors duration-200">
                                                Xem ngay
                                            </a>
                                        </div>
                                        <div class="flex-1 flex justify-center">
                                            <img src="{{ asset('img/hero-accessories-1.png') }}" alt="Gaming Accessories"
                                                class="max-w-full h-auto">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </div>

                <!-- Side Banners -->
                <div class="xl:max-w-[393px] w-full">
                    <div class="flex flex-col sm:flex-row xl:flex-col gap-5">
                        <!-- Banner 1 -->
                        <div class="w-full relative rounded-lg bg-white p-6 shadow-lg min-h-[200px]">
                            <div class="flex justify-between items-center h-full">
                                <div class="flex-1 pr-4">
                                    <h2 class="font-semibold text-gray-900 text-xl mb-6 leading-tight">
                                        <a href="{{ route('products.index', ['category' => 7]) }}">
                                            Bàn phím cơ<br>AULA F99
                                        </a>
                                    </h2>
                                    <div class="space-y-3">
                                        <p class="font-medium text-gray-500 text-sm">
                                            Gaming Keyboard
                                        </p>
                                        <div class="space-y-1">
                                            <div class="font-medium text-2xl text-red-600">1.550.000 VNĐ</div>
                                            <div class="font-medium text-lg text-gray-400 line-through">1.990.000 VNĐ</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('img/keyboard-banner.png') }}" alt="AULA F99 Keyboard"
                                        class="w-32 h-28 object-contain">
                                </div>
                            </div>
                        </div>

                        <!-- Banner 2 -->
                        <div
                            class="w-full relative rounded-lg bg-gradient-to-br from-blue-600 to-purple-600 p-6 text-white shadow-lg min-h-[200px]">
                            <div class="flex justify-between items-center h-full">
                                <div class="flex-1 pr-4">
                                    <h2 class="font-semibold text-xl text-white mb-6 leading-tight">
                                        <a href="{{ route('products.index', ['category' => 7]) }}" class="text-white">
                                            Gaming<br>Monitor 4K
                                        </a>
                                    </h2>
                                    <div class="space-y-3">
                                        <p class="font-medium text-blue-100 text-sm">
                                            Siêu khuyến mãi
                                        </p>
                                        <div class="space-y-1">
                                            <div class="font-medium text-2xl text-yellow-400">8.990.000 VNĐ</div>
                                            <div class="font-medium text-lg text-blue-200 line-through">12.990.000 VNĐ</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('img/monitor-4k.png') }}" alt="4K Monitor"
                                        class="w-32 h-28 object-contain">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hero Features -->
        <div class="max-w-[1060px] w-full mx-auto px-4 sm:px-8 xl:px-0">
            <div class="flex flex-wrap items-center gap-8 xl:gap-12 mt-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-lg text-gray-900">Giao hàng miễn phí</h3>
                        <p class="text-sm text-gray-600">Đơn hàng trên 2 triệu</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-lg text-gray-900">Bảo hành chính hãng</h3>
                        <p class="text-sm text-gray-600">12-36 tháng</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-lg text-gray-900">Hỗ trợ 24/7</h3>
                        <p class="text-sm text-gray-600">Tư vấn nhiệt tình</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-lg text-gray-900">Đổi trả dễ dàng</h3>
                        <p class="text-sm text-gray-600">Trong vòng 7 ngày</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="overflow-hidden pt-20">
        <div class="max-w-[1170px] w-full mx-auto px-4 sm:px-8 xl:px-0 pb-16 border-b border-gray-200">
            <div class="swiper categories-carousel">
                <!-- Section Title -->
                <div class="mb-10 flex items-center justify-between">
                    <div>
                        <span class="flex items-center gap-3 font-medium text-gray-900 mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                            </svg>
                            Danh mục
                        </span>
                        <h2 class="font-semibold text-xl xl:text-3xl text-gray-900">
                            Khám phá theo danh mục
                        </h2>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center gap-3">
                        <button
                            class="categories-prev w-12 h-12 rounded-full bg-gray-100 hover:bg-blue-600 hover:text-white transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button
                            class="categories-next w-12 h-12 rounded-full bg-gray-100 hover:bg-blue-600 hover:text-white transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Categories Swiper -->
                <div class="swiper categories-swiper">
                    <div class="swiper-wrapper">
                        @foreach($categories as $category)
                            <div class="swiper-slide">
                                <a href="{{ route('products.index', ['category' => $category->id]) }}"
                                    class="group block p-6 bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-blue-600">
                                    <div class="text-center">
                                        <div
                                            class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-600 transition-colors">
                                            <svg class="w-8 h-8 text-blue-600 group-hover:text-white" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                            </svg>
                                        </div>
                                        <h3
                                            class="font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                                            {{ $category->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            {{ $category->products_count ?? 0 }} sản phẩm
                                        </p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- New Arrivals Section -->
    <section class="overflow-hidden pt-16">
        <div class="max-w-[1170px] w-full mx-auto px-4 sm:px-8 xl:px-0">
            <!-- Section Title -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <span class="flex items-center gap-3 font-medium text-gray-900 mb-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Mới nhất
                    </span>
                    <h2 class="font-semibold text-xl xl:text-3xl text-gray-900">
                        Sản phẩm mới
                    </h2>
                </div>
                <a href="{{ route('products.index') }}"
                    class="inline-flex font-medium text-sm py-3 px-8 rounded-md border border-gray-300 bg-gray-50 text-gray-900 hover:bg-gray-900 hover:text-white hover:border-transparent transition-all duration-200">
                    Xem tất cả
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($newProducts->take(8) as $product)
                    <div
                        class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-blue-600 overflow-hidden">
                        <div class="relative overflow-hidden">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">

                            @if($product->is_featured)
                                <div class="absolute top-3 left-3 bg-red-600 text-white text-xs px-2 py-1 rounded">
                                    HOT
                                </div>
                            @endif

                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300">
                            </div>

                            <div
                                class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button onclick="addToCart({{ $product->id }})"
                                    class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg hover:bg-blue-600 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9m-9 0l1.5-6m7.5 6v0" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="p-5">
                            <h3
                                class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                <a href="{{ route('products.show', $product) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-lg text-blue-600">{{ $product->formatted_price }}</p>
                                    @if($product->stock > 0)
                                        <p class="text-sm text-green-600">Còn hàng</p>
                                    @else
                                        <p class="text-sm text-red-600">Hết hàng</p>
                                    @endif
                                </div>

                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Promo Banner Section -->
    <section class="overflow-hidden py-20">
        <div class="max-w-[1170px] w-full mx-auto px-4 sm:px-8 xl:px-0">
            <div
                class="relative z-10 overflow-hidden rounded-lg bg-gray-100 py-16 lg:py-20 xl:py-24 px-4 sm:px-8 lg:px-16 xl:px-20 mb-8">
                <div class="max-w-[550px] w-full">
                    <span class="block font-medium text-xl text-gray-900 mb-3">
                        Apple MacBook Air M2
                    </span>
                    <h2 class="font-bold text-xl lg:text-3xl xl:text-4xl text-gray-900 mb-5">
                        GIẢM GIÁ TỚI 20%
                    </h2>
                    <p class="text-gray-600 mb-8 max-w-md">
                        Trải nghiệm sức mạnh của chip M2 với thiết kế siêu mỏng nhẹ.
                        Thời lượng pin cả ngày và hiệu năng vượt trội.
                    </p>
                    <a href="{{ route('products.index', ['category' => 5]) }}"
                        class="inline-flex font-medium text-white bg-blue-600 py-3 px-8 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        Mua ngay
                    </a>
                </div>

                <!-- Background Image -->
                <div class="absolute right-0 bottom-0 hidden lg:block">
                    <img src="{{ asset('img/macbook-banner.png') }}" alt="MacBook Air M2" class="max-w-md">
                </div>
            </div>
        </div>
    </section>

    <!-- Best Sellers Section -->
    <section class="overflow-hidden">
        <div class="max-w-[1170px] w-full mx-auto px-4 sm:px-8 xl:px-0">
            <!-- Section Title -->
            <div class="mb-10 flex items-center justify-between">
                <div>
                    <span class="flex items-center gap-3 font-medium text-gray-900 mb-2">
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        Tháng này
                    </span>
                    <h2 class="font-semibold text-xl xl:text-3xl text-gray-900">
                        Bán chạy nhất
                    </h2>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($popularProducts->take(6) as $product)
                    <div
                        class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-blue-600 overflow-hidden">
                        <div class="relative overflow-hidden">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">

                            <div class="absolute top-3 left-3 bg-orange-600 text-white text-xs px-2 py-1 rounded">
                                Best Seller
                            </div>

                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300">
                            </div>
                        </div>

                        <div class="p-5">
                            <h3
                                class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                <a href="{{ route('products.show', $product) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-lg text-blue-600">{{ $product->formatted_price }}</p>
                                    <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                                </div>

                                <button onclick="addToCart({{ $product->id }})"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors text-sm">
                                    Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('products.index') }}"
                    class="inline-flex font-medium text-sm py-3 px-12 rounded-md border border-gray-300 bg-gray-50 text-gray-900 hover:bg-gray-900 hover:text-white hover:border-transparent transition-all duration-200">
                    Xem tất cả
                </a>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="overflow-hidden py-20">
        <div class="max-w-[1170px] mx-auto px-4 sm:px-8 xl:px-0">
            <div
                class="relative z-10 overflow-hidden rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-4 sm:px-8 xl:px-12 py-12">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                    <div class="max-w-[491px] w-full text-white">
                        <h2 class="font-bold text-2xl xl:text-3xl mb-4">
                            Đăng ký nhận tin khuyến mãi
                        </h2>
                        <p class="text-blue-100 mb-6">
                            Nhận ngay thông tin về sản phẩm mới và các chương trình khuyến mãi đặc biệt từ PC Shop.
                        </p>
                    </div>

                    <div class="max-w-[400px] w-full">
                        <form class="flex gap-3">
                            <input type="email" placeholder="Nhập email của bạn..."
                                class="flex-1 px-4 py-3 rounded-lg border-0 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white">
                            <button type="submit"
                                class="bg-white text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                                Đăng ký
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Hero Carousel
            const heroSwiper = new Swiper('.hero-carousel', {
                slidesPerView: 1,
                spaceBetween: 0,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                loop: true,
            });

            // Categories Carousel
            const categoriesSwiper = new Swiper('.categories-swiper', {
                slidesPerView: 6,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.categories-next',
                    prevEl: '.categories-prev',
                },
                breakpoints: {
                    0: {
                        slidesPerView: 2,
                    },
                    640: {
                        slidesPerView: 3,
                    },
                    1024: {
                        slidesPerView: 4,
                    },
                    1200: {
                        slidesPerView: 6,
                    },
                },
            });
        });
    </script>
@endpush