@extends('layouts.app')

@section('title', 'Giỏ hàng - PC Shop')

@section('content')
<!-- Breadcrumb Section -->
<section class="bg-gray-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-600" aria-label="Breadcrumb">
            <a href="{{ route('welcome') }}" class="hover:text-blue-600 transition-colors whitespace-nowrap">Trang chủ</a>
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-900 font-medium whitespace-nowrap">Giỏ hàng</span>
        </nav>
    </div>
</section>

<!-- Cart Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Giỏ hàng của bạn</h1>
            @if(!$cartItems->isEmpty())
                <p class="text-gray-600">Bạn có {{ $cartItems->count() }} sản phẩm trong giỏ hàng</p>
            @endif
        </div>

        @if($cartItems->isEmpty())
            <!-- Empty Cart -->
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Giỏ hàng trống</h3>
                <p class="text-gray-600 mb-8">Hãy thêm một số sản phẩm tuyệt vời vào giỏ hàng của bạn!</p>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tiếp tục mua sắm
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Sản phẩm trong giỏ hàng</h2>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                <div class="p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start space-x-4">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0">
                                            <a href="{{ route('products.show', $item->product) }}">
                                                @if($item->product->image)
                                                    <img src="{{ $item->product->image_url }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="w-20 h-20 rounded-lg object-cover hover:scale-105 transition-transform duration-200">
                                                @else
                                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </a>
                                        </div>
                                        
                                        <!-- Product Details -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h3 class="text-base font-medium text-gray-900 hover:text-blue-600 transition-colors">
                                                        <a href="{{ route('products.show', $item->product) }}">
                                                            {{ $item->product->name }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-sm text-gray-500 mt-1">{{ $item->product->category->name }}</p>
                                                    <div class="flex items-center mt-2">
                                                        @if($item->product->in_stock)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                </svg>
                                                                Còn hàng
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                                Hết hàng
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Remove Button -->
                                                <button 
                                                    onclick="removeFromCart({{ $item->product->id }})"
                                                    class="text-gray-400 hover:text-red-600 transition-colors p-1"
                                                    title="Xóa sản phẩm"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <!-- Price and Quantity -->
                                            <div class="flex items-center justify-between mt-4">
                                                <div class="flex items-center space-x-4">
                                                    <!-- Quantity Selector -->
                                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                                        <button 
                                                            onclick="updateQuantity({{ $item->product->id }}, {{ $item->quantity - 1 }})" 
                                                            class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors rounded-l-lg"
                                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}
                                                        >
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                            </svg>
                                                        </button>
                                                        <span class="w-12 h-10 flex items-center justify-center text-sm font-medium">{{ $item->quantity }}</span>
                                                        <button 
                                                            onclick="updateQuantity({{ $item->product->id }}, {{ $item->quantity + 1 }})" 
                                                            class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors rounded-r-lg"
                                                            {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}
                                                        >
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <!-- Item Total Price -->
                                                <div class="text-right">
                                                    <p class="text-lg font-bold text-blue-600">
                                                        {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }} VNĐ
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ number_format($item->product->price, 0, ',', '.') }} VNĐ x {{ $item->quantity }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Continue Shopping Button -->
                    <div class="mt-6">
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-2xl p-6 shadow-sm border border-gray-200 sticky top-24">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Tóm tắt đơn hàng</h2>
                        
                        <div class="space-y-4">
                            <!-- Subtotal -->
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Tạm tính:</span>
                                <span class="font-medium" id="subtotal">
                                    {{ number_format($cartItems->sum(function ($item) { return $item->product->price * $item->quantity; }), 0, ',', '.') }} VNĐ
                                </span>
                            </div>
                            
                            <!-- Shipping -->
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <span class="text-gray-600">Phí vận chuyển:</span>
                                    <button class="ml-1 text-gray-400 hover:text-gray-600" title="Thông tin phí vận chuyển">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                </div>
                                <span class="font-medium">{{ number_format(30000, 0, ',', '.') }} VNĐ</span>
                            </div>
                            
                            <!-- Tax -->
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <span class="text-gray-600">Thuế VAT (10%):</span>
                                    <button class="ml-1 text-gray-400 hover:text-gray-600" title="Thông tin thuế">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                </div>
                                <span class="font-medium" id="tax">
                                    {{ number_format($cartItems->sum(function ($item) { return $item->product->price * $item->quantity; }) * 0.1, 0, ',', '.') }} VNĐ
                                </span>
                            </div>
                            
                            <!-- Discount Code -->
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex space-x-2">
                                    <input 
                                        type="text" 
                                        placeholder="Mã giảm giá" 
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                    <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                                        Áp dụng
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Total -->
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold text-gray-900">Tổng cộng:</span>
                                    <span class="text-xl font-bold text-blue-600" id="total">
                                        {{ number_format($cartItems->sum(function ($item) { return $item->product->price * $item->quantity; }) * 1.1 + 30000, 0, ',', '.') }} VNĐ
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Checkout Button -->
                        <div class="mt-8">
                            <a href="{{ route('checkout.index') }}" 
                               class="w-full bg-blue-600 text-white py-4 rounded-xl font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Tiến hành thanh toán
                            </a>
                        </div>
                        
                        <!-- Security Info -->
                        <div class="mt-6 flex items-center justify-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Thanh toán an toàn và bảo mật
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
function updateQuantity(productId, newQuantity) {
    if (newQuantity < 1) return;
    
    fetch(`/cart/update-product/${productId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            quantity: parseInt(newQuantity)
        })
    })
    .then(response => {
        console.log('Update response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Update response data:', data);
        if (data.success) {
            // Reload the page to update the cart display
            location.reload();
        } else {
            showNotification(data.message || 'Có lỗi xảy ra. Vui lòng thử lại.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra: ' + error.message, 'error');
    });
}

function removeFromCart(productId) {
    if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) return;

    fetch(`/cart/remove-product/${productId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showNotification(data.message || 'Đã xóa sản phẩm khỏi giỏ hàng', 'success');
            // Reload the page to update the cart display
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Có lỗi xảy ra. Vui lòng thử lại.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra: ' + error.message, 'error');
    });
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' :
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
</script>
@endpush