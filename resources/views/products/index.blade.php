@extends('layouts.app')

@section('title', 'Sản phẩm - PC Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex gap-8">
        <!-- Sidebar Filters -->
        <div class="w-80 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Filters:</h2>
                    <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Clean All
                    </a>
                </div>

                <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                    <!-- Search -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Tìm kiếm sản phẩm..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-6">
                        <h3 class="text-base font-medium text-gray-900 mb-3 flex justify-between items-center">
                            Category
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </h3>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($categories as $category)
                                <label class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <input type="radio" name="category" value="{{ $category->id }}" 
                                               {{ (string)request('category') == (string)$category->id ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $category->products_count ?? 0 }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="mb-6">
                        <h3 class="text-base font-medium text-gray-900 mb-3 flex justify-between items-center">
                            Price Range
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </h3>
                        <div class="space-y-3">
                            <!-- Custom Price Range -->
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                       placeholder="Từ" 
                                       class="px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                       placeholder="Đến" 
                                       class="px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <!-- Predefined Price Ranges -->
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="price_range" value="0-500000" 
                                           {{ request('price_range') == '0-500000' ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Dưới 500.000đ</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="price_range" value="500000-1000000" 
                                           {{ request('price_range') == '500000-1000000' ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">500.000đ - 1.000.000đ</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="price_range" value="1000000-5000000" 
                                           {{ request('price_range') == '1000000-5000000' ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">1.000.000đ - 5.000.000đ</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="price_range" value="5000000-10000000" 
                                           {{ request('price_range') == '5000000-10000000' ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">5.000.000đ - 10.000.000đ</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="price_range" value="10000000-999999999" 
                                           {{ request('price_range') == '10000000-999999999' ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Trên 10.000.000đ</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Filter -->
                    <div class="mb-6">
                        <h3 class="text-base font-medium text-gray-900 mb-3">Availability</h3>
                        <label class="flex items-center">
                            <input type="checkbox" name="in_stock" value="1" 
                                   {{ request('in_stock') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Còn hàng</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Áp dụng bộ lọc
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header with Sort -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        @if(request('category'))
                            {{ $categories->where('id', request('category'))->first()->name ?? 'Sản phẩm' }}
                        @else
                            Tất cả sản phẩm
                        @endif
                    </h1>
                    <p class="text-gray-600">
                        Showing {{ $products->firstItem() ?? 0 }} of {{ $products->total() }} Products
                    </p>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Sort Dropdown -->
                    <div class="flex items-center gap-2">
                        <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-2">
                            @foreach(request()->except(['sort', 'page']) as $key => $value)
                                @if(is_array($value))
                                    @foreach($value as $val)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $val }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            
                            <select name="sort" onchange="this.form.submit()" 
                                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Latest Products</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A to Z</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                            </select>
                        </form>
                    </div>

                    <!-- View Toggle -->
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button class="p-2 rounded bg-blue-600 text-white">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>
                        <button class="p-2 rounded text-gray-500 hover:text-gray-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @forelse($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow group">
                        <!-- Product Image -->
                        <div class="aspect-square bg-gray-100 relative overflow-hidden">
                            @if($product->image)
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     onerror="this.src='{{ asset('img/placeholder.jpg') }}'; this.onerror=null;">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex flex-col gap-2">
                                @if($product->is_featured)
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-medium">HOT</span>
                                @endif
                                @if($product->stock <= 0)
                                    <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded font-medium">Hết hàng</span>
                                @endif
                            </div>

                            <!-- Quick Actions -->
                            <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="addToWishlist({{ $product->id }})" 
                                        class="p-2 bg-white rounded-full shadow-md hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                                <button onclick="quickView({{ $product->id }})" 
                                        class="p-2 bg-white rounded-full shadow-md hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <!-- Category -->
                            @if($product->category)
                                <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded font-medium">{{ $product->category->name }}</span>
                            @endif
                            
                            <!-- Product Name -->
                            <h3 class="font-semibold text-gray-900 mt-2 mb-2 line-clamp-2 hover:text-blue-600 transition-colors">
                                <a href="{{ route('products.show', $product) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            
                            <!-- Price -->
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg font-bold text-blue-600">{{ number_format((float)$product->price, 0, ',', '.') }} VNĐ</span>
                                @if($product->original_price && $product->original_price > $product->price)
                                    <span class="text-sm text-gray-500 line-through">{{ number_format((float)$product->original_price, 0, ',', '.') }} VNĐ</span>
                                @endif
                            </div>
                            
                            <!-- Stock Status -->
                            @if($product->stock > 0)
                                <p class="text-xs text-green-600 mb-3">
                                    <span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                    Còn {{ $product->stock }} sản phẩm
                                </p>
                            @else
                                <p class="text-xs text-red-600 mb-3">
                                    <span class="inline-block w-2 h-2 bg-red-500 rounded-full mr-1"></span>
                                    Hết hàng
                                </p>
                            @endif
                            
                            <!-- Actions -->
                            <div class="flex gap-2">
                                @if($product->stock > 0)
                                    <button onclick="addToCart({{ $product->id }})" 
                                            class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded hover:bg-blue-700 transition-colors text-sm font-medium">
                                        Add to Cart
                                    </button>
                                @else
                                    <button disabled 
                                            class="flex-1 bg-gray-300 text-gray-500 text-center py-2 px-4 rounded cursor-not-allowed text-sm font-medium">
                                        Hết hàng
                                    </button>
                                @endif
                                <a href="{{ route('products.show', $product) }}" 
                                   class="bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-gray-200 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Không tìm thấy sản phẩm</h3>
                        <p class="text-gray-600 mb-4">Thử thay đổi bộ lọc hoặc tìm kiếm với từ khóa khác.</p>
                        <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Xem tất cả sản phẩm
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="flex justify-center">
                    {{ $products->appends(request()->input())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Handle price range radio buttons
    document.querySelectorAll('input[name="price_range"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                // Clear custom price inputs when selecting predefined range
                document.querySelector('input[name="min_price"]').value = '';
                document.querySelector('input[name="max_price"]').value = '';
            }
        });
    });

    // Clear price range when custom input is used
    document.querySelectorAll('input[name="min_price"], input[name="max_price"]').forEach(input => {
        input.addEventListener('input', function() {
            document.querySelectorAll('input[name="price_range"]').forEach(radio => {
                radio.checked = false;
            });
        });
    });

    // Auto-submit form when filter changes
    document.querySelectorAll('#filterForm input[type="radio"], #filterForm input[type="checkbox"]').forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });

    // Add to cart functionality
    function addToCart(productId) {
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count if exists
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cart_count;
                }
                
                // Show success message
                alert('Sản phẩm đã được thêm vào giỏ hàng!');
            } else {
                alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!');
        });
    }

    // Wishlist functionality (placeholder)
    function addToWishlist(productId) {
        alert('Tính năng wishlist sẽ được cập nhật sớm!');
    }

    // Quick view functionality (placeholder)
    function quickView(productId) {
        window.location.href = `/products/${productId}`;
    }
</script>
@endsection