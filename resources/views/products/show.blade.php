@extends('layouts.app')

@section('title', $product->name . ' - PC Shop')
@section('meta')
    <meta name="description" content="{{ Str::limit(strip_tags($product->description), 160) }}">
    <meta property="og:title" content="{{ $product->name }} - PC Shop">
    <meta property="og:description" content="{{ Str::limit(strip_tags($product->description), 160) }}">
    <meta property="og:image" content="{{ $product->image_url }}">
    <meta property="og:url" content="{{ url()->current() }}">
@endsection

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
                <a href="{{ route('products.index') }}" class="hover:text-blue-600 transition-colors whitespace-nowrap">Sản
                    phẩm</a>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('products.category', $product->category) }}"
                    class="hover:text-blue-600 transition-colors whitespace-nowrap">{{ $product->category->name }}</a>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900 font-medium truncate max-w-xs">{{ $product->name }}</span>
            </nav>
        </div>
    </section>

    <!-- Product Detail Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <!-- Product Images -->
                <div x-data="{ mainImage: '{{ $product->image_url }}' }" class="space-y-6">
                    <!-- Main Image -->
                    <div class="relative w-full h-96 lg:h-[500px] bg-gray-100 rounded-2xl overflow-hidden">
                        <img :src="mainImage" alt="{{ $product->name }}" class="w-full h-full object-cover cursor-zoom-in"
                            onclick="openImageModal(this.src)">

                        @if($product->is_featured)
                            <div
                                class="absolute top-4 left-4 bg-red-600 text-white text-sm font-medium px-3 py-1 rounded-full z-10">
                                HOT
                            </div>
                        @endif

                        @if(!$product->in_stock)
                            <div
                                class="absolute top-4 right-4 bg-gray-900 text-white text-sm font-medium px-3 py-1 rounded-full z-10">
                                Hết hàng
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnail Images -->
                    @if($product->all_images && count($product->all_images) > 1)
                        <div class="grid grid-cols-4 gap-4">
                            @foreach($product->all_images as $index => $image)
                                <button @click="mainImage = '{{ asset($image) }}'"
                                    class="aspect-w-1 aspect-h-1 bg-gray-100 rounded-lg overflow-hidden border-2 transition-all duration-200"
                                    :class="mainImage === '{{ asset($image) }}' ? 'border-blue-600' : 'border-gray-200 hover:border-gray-300'">
                                    <img src="{{ asset($image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Information -->
                <div class="space-y-8">
                    <!-- Product Title & Category -->
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                                {{ $product->category->name }}
                            </span>
                            @if($product->is_featured)
                                <span class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full">
                                    Nổi bật
                                </span>
                            @endif
                        </div>

                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                        <!-- Rating & Views -->
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                                <span class="text-sm text-gray-600 ml-2">(4.0)</span>
                            </div>
                            <span class="text-sm text-gray-500">{{ $product->views ?? 0 }} lượt xem</span>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="border-t border-b border-gray-200 py-6">
                        <div class="text-4xl font-bold text-blue-600 mb-2">{{ $product->formatted_price }}</div>
                        @if($product->original_price && $product->original_price > $product->price)
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-lg text-gray-500 line-through">{{ number_format($product->original_price, 0, ',', '.') }}đ</span>
                                <span class="bg-red-100 text-red-800 text-sm font-medium px-2 py-1 rounded">
                                    -{{ number_format((($product->original_price - $product->price) / $product->original_price) * 100, 0) }}%
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Stock Status -->
                    <div data-product-id="{{ $product->id }}">
                        @if($product->in_stock)
                            <div class="flex items-center gap-2 text-green-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="font-medium">Còn hàng</span>
                                <span data-stock-display="{{ $product->id }}"
                                    class="text-sm text-gray-500">({{ $product->stock }} sản phẩm)</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span class="font-medium">Hết hàng</span>
                            </div>
                        @endif
                    </div>

                    <!-- Quantity & Add to Cart -->
                    @if($product->in_stock)
                        <div x-data="{ quantity: 1 }" class="space-y-6">
                            <!-- Quantity Selector -->
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">Số lượng:</label>
                                <div class="flex items-center border border-gray-300 rounded-lg w-fit">
                                    <button @click="quantity = Math.max(1, quantity - 1)"
                                        class="w-12 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors rounded-l-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 12H4" />
                                        </svg>
                                    </button>
                                    <input x-model="quantity" type="number" min="1" max="{{ $product->stock }}"
                                        class="w-16 h-12 text-center border-0 focus:ring-0 focus:border-0">
                                    <button @click="quantity = Math.min({{ $product->stock }}, quantity + 1)"
                                        class="w-12 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors rounded-r-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button @click="addToCartWithQuantity({{ $product->id }}, quantity)" data-add-to-cart
                                    data-product-id="{{ $product->id }}"
                                    class="flex-1 bg-blue-600 text-white px-8 py-4 rounded-xl font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9m-9 0l1.5-6m7.5 6v0" />
                                    </svg>
                                    Thêm vào giỏ hàng
                                </button>

                                <button
                                    class="sm:w-auto w-full bg-gray-100 text-gray-900 px-6 py-4 rounded-xl font-semibold hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    Yêu thích
                                </button>
                            </div>
                        </div>
                    @else
                        <button disabled
                            class="w-full bg-gray-300 text-gray-500 px-8 py-4 rounded-xl font-semibold cursor-not-allowed flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636" />
                            </svg>
                            Hết hàng
                        </button>
                    @endif

                    <!-- Product Features -->
                    <div class="grid grid-cols-3 gap-6 py-6 border-t border-gray-200">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Giao hàng miễn phí</p>
                            <p class="text-xs text-gray-600">Đơn hàng > 5tr</p>
                        </div>

                        <div class="text-center">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Bảo hành chính hãng</p>
                            <p class="text-xs text-gray-600">12-24 tháng</p>
                        </div>

                        <div class="text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Đổi trả dễ dàng</p>
                            <p class="text-xs text-gray-600">Trong 7 ngày</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Details Tabs -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div x-data="{ activeTab: 'description' }" class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="flex" aria-label="Tabs">
                        <button @click="activeTab = 'description'"
                            :class="activeTab === 'description' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                            Mô tả sản phẩm
                        </button>

                        @if($product->specifications && count($product->specifications) > 0)
                            <button @click="activeTab = 'specifications'"
                                :class="activeTab === 'specifications' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                                Thông số kỹ thuật
                            </button>
                        @endif

                        <button @click="activeTab = 'reviews'"
                            :class="activeTab === 'reviews' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                            Đánh giá (0)
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-8">
                    <!-- Description Tab -->
                    <div x-show="activeTab === 'description'" class="prose max-w-none">
                        @if($product->description)
                            <div class="text-gray-700 leading-relaxed">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        @else
                            <p class="text-gray-500 italic">Chưa có mô tả cho sản phẩm này.</p>
                        @endif
                    </div>

                    <!-- Specifications Tab -->
                    @if($product->specifications && count($product->specifications) > 0)
                        <div x-show="activeTab === 'specifications'">
                            <div class="bg-gray-50 rounded-xl p-6">
                                <div class="space-y-4">
                                    @foreach($product->specifications as $key => $value)
                                        <div class="flex justify-between py-3 border-b border-gray-200 last:border-0">
                                            <dt class="font-medium text-gray-900">{{ $key }}:</dt>
                                            <dd class="text-gray-700">{{ $value }}</dd>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Reviews Tab -->
                    <div x-show="activeTab === 'reviews'">
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có đánh giá</h3>
                            <p class="text-gray-600">Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Sản phẩm liên quan</h2>
                    <p class="text-gray-600">Khám phá thêm những sản phẩm tương tự</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($relatedProducts as $relatedProduct)
                        <div
                            class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-blue-600 overflow-hidden">
                            <div class="relative overflow-hidden">
                                <img src="{{ $relatedProduct->image_url }}" alt="{{ $relatedProduct->name }}"
                                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">

                                @if($relatedProduct->is_featured)
                                    <div class="absolute top-3 left-3 bg-red-600 text-white text-xs px-2 py-1 rounded">
                                        HOT
                                    </div>
                                @endif

                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300">
                                </div>

                                <div
                                    class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    @if($relatedProduct->in_stock)
                                        <button onclick="addToCart({{ $relatedProduct->id }})"
                                            class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg hover:bg-blue-600 hover:text-white transition-colors"
                                            title="Thêm vào giỏ hàng">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9m-9 0l1.5-6m7.5 6v0" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="p-5">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                        {{ $relatedProduct->category->name }}
                                    </span>
                                    @if($relatedProduct->in_stock)
                                        <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded">
                                            Còn hàng
                                        </span>
                                    @else
                                        <span class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded">
                                            Hết hàng
                                        </span>
                                    @endif
                                </div>

                                <h3
                                    class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    <a href="{{ route('products.show', $relatedProduct) }}">
                                        {{ $relatedProduct->name }}
                                    </a>
                                </h3>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-bold text-lg text-blue-600">{{ $relatedProduct->formatted_price }}</p>
                                    </div>

                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3 h-3 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor"
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
    @endif

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4"
        tabindex="-1" role="dialog" aria-modal="true" onclick="closeImageModal()">
        <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
            <button onclick="closeImageModal()" title="Đóng (ESC)"
                class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors bg-black bg-opacity-50 rounded-full p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Open image modal
        function openImageModal(src) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            if (modal && modalImage) {
                modalImage.src = src;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';

                // Focus on modal for better keyboard navigation
                modal.focus();
            }
        }

        // Close image modal
        function closeImageModal() {
            const modal = document.getElementById('imageModal');

            if (modal && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';

                // Clear the image source to save memory
                const modalImage = document.getElementById('modalImage');
                if (modalImage) {
                    modalImage.src = '';
                }
            }
        }

        // Add to cart with quantity from Alpine.js component
        function addToCartWithQuantity(productId, quantity) {
            console.log('Adding product to cart:', productId, 'quantity:', quantity);

            const data = {
                product_id: productId,
                quantity: parseInt(quantity)
            };

            // Show loading notification
            showNotification('Đang thêm sản phẩm...', 'info');

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);

                    return response.json().then(jsonData => {
                        return { status: response.status, ok: response.ok, data: jsonData };
                    });
                })
                .then(({ status, ok, data }) => {
                    console.log('Full response:', { status, ok, data });

                    if (ok && data.success) {
                        showNotification(`Đã thêm ${quantity} sản phẩm vào giỏ hàng!`, 'success');

                        // Update cart count
                        const cartCount = document.querySelector('.cart-counter');
                        if (cartCount && data.cart_count) {
                            cartCount.textContent = data.cart_count;
                        }

                        // Update cart sidebar
                        if (typeof updateCartSidebar === 'function') {
                            updateCartSidebar();
                        }
                    } else {
                        const errorMessage = data.message || 'Có lỗi xảy ra khi thêm sản phẩm.';
                        console.log('Error message:', errorMessage);
                        showNotification(errorMessage, 'error');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    showNotification('Có lỗi kết nối. Vui lòng thử lại.', 'error');
                });
        }

        // Add to cart (for related products)
        function addToCart(productId, quantity = 1) {
            addToCartWithQuantity(productId, quantity);
        }

        // Update cart sidebar
        function updateCartSidebar() {
            fetch('/cart/items')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart items in sidebar if cart is open
                        const cartSidebar = document.getElementById('cart-sidebar');
                        if (cartSidebar && !cartSidebar.classList.contains('translate-x-full')) {
                            window.location.reload(); // Simple reload for now
                        }
                    }
                })
                .catch(error => {
                    console.error('Error updating cart sidebar:', error);
                });
        }

        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full ${type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                    'bg-blue-500 text-white'
                }`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Show notification
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Hide notification after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Close modal on escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' || e.key === 'Esc') {
                const modal = document.getElementById('imageModal');
                if (!modal.classList.contains('hidden')) {
                    closeImageModal();
                }
            }
        });

        // Close modal when clicking outside image
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('imageModal');
            if (modal) {
                modal.addEventListener('click', function (e) {
                    if (e.target === this) {
                        closeImageModal();
                    }
                });
            }
        });
    </script>
@endpush