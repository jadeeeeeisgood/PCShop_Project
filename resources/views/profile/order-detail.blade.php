@extends('layouts.app')

@section('title', 'Chi Tiết Đơn Hàng #' . $order->id)

@section('content')
<!-- Breadcrumb Section -->
<section class="bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm text-gray-600 mb-4" aria-label="Breadcrumb">
            <a href="{{ route('welcome') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('profile.orders') }}" class="hover:text-blue-600 transition-colors">Đơn hàng của tôi</a>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-gray-900 font-medium">Đơn hàng #{{ $order->id }}</span>
        </nav>
        
        <!-- Order Status Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Đơn hàng #{{ $order->id }}</h1>
                    <p class="text-gray-600">Đặt hàng lúc {{ $order->created_at->format('H:i, d/m/Y') }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Order Status -->
                    @switch($order->status)
                        @case('pending')
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-yellow-400 rounded-full animate-pulse"></div>
                                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Chờ xử lý</span>
                            </div>
                            @break
                        @case('processing')
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-blue-400 rounded-full animate-pulse"></div>
                                <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">Đang xử lý</span>
                            </div>
                            @break
                        @case('shipped')
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-purple-400 rounded-full"></div>
                                <span class="px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">Đã gửi hàng</span>
                            </div>
                            @break
                        @case('delivered')
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">Đã giao hàng</span>
                            </div>
                            @break
                        @case('canceled')
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-medium">Đã hủy</span>
                            </div>
                            @break
                        @default
                            <span class="px-4 py-2 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">{{ ucfirst($order->status) }}</span>
                    @endswitch
                    
                    <!-- Payment Status -->
                    @switch($order->payment_status)
                        @case('pending')
                            <span class="px-4 py-2 bg-orange-100 text-orange-800 rounded-full text-sm font-medium">Chờ thanh toán</span>
                            @break
                        @case('paid')
                            <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">Đã thanh toán</span>
                            @break
                        @case('failed')
                            <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-medium">Thanh toán thất bại</span>
                            @break
                        @default
                            <span class="px-4 py-2 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">{{ ucfirst($order->payment_status) }}</span>
                    @endswitch
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Order Details Section -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column - Order Items & Details -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Order Items -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Sản phẩm đã đặt</h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($order->orderItems as $item)
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ $item->product->image_url }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-20 h-20 object-cover rounded-xl border border-gray-200">
                                    @else
                                        <div class="w-20 h-20 bg-gray-100 rounded-xl border border-gray-200 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Product Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                        {{ $item->product_name ?: ($item->product ? $item->product->name : 'Sản phẩm không còn tồn tại') }}
                                    </h3>
                                    @if($item->product && $item->product->sku)
                                        <p class="text-sm text-gray-500 mb-2">SKU: {{ $item->product->sku }}</p>
                                    @endif
                                    <div class="flex items-center gap-4 text-sm">
                                        <span class="text-gray-600">Đơn giá: <span class="font-medium text-gray-900">{{ number_format($item->price, 0, ',', '.') }}₫</span></span>
                                        <span class="text-gray-600">Số lượng: <span class="font-medium text-gray-900">{{ $item->quantity }}</span></span>
                                    </div>
                                </div>
                                
                                <!-- Item Total -->
                                <div class="text-right">
                                    <p class="text-lg font-bold text-blue-600">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Customer & Shipping Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Shipping Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Thông tin giao hàng</h3>
                        </div>
                        <div class="space-y-2">
                            <p class="font-semibold text-gray-900">{{ $order->customer_name }}</p>
                            <p class="text-gray-600">{{ $order->customer_phone }}</p>
                            <p class="text-gray-600">{{ $order->customer_email }}</p>
                            <p class="text-gray-600 leading-relaxed">{{ $order->customer_address }}</p>
                        </div>
                    </div>
                    
                    <!-- Payment Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Thông tin thanh toán</h3>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Phương thức thanh toán</p>
                                @switch($order->payment_method)
                                    @case('cod')
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 bg-yellow-100 rounded-lg flex items-center justify-center">
                                                <span class="text-yellow-600 text-xs">💰</span>
                                            </div>
                                            <span class="font-medium text-gray-900">Thanh toán khi nhận hàng</span>
                                        </div>
                                        @break
                                    @case('vnpay')
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <span class="text-blue-600 text-xs">🏦</span>
                                            </div>
                                            <span class="font-medium text-gray-900">VNPay</span>
                                        </div>
                                        @break
                                    @case('paypal')
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <span class="text-blue-600 text-xs">🅿️</span>
                                            </div>
                                            <span class="font-medium text-gray-900">PayPal</span>
                                        </div>
                                        @break
                                    @default
                                        <span class="font-medium text-gray-900">{{ ucfirst($order->payment_method) }}</span>
                                @endswitch
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Trạng thái thanh toán</p>
                                @switch($order->payment_status)
                                    @case('pending')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-orange-100 text-orange-800 rounded-lg text-sm font-medium">
                                            <div class="w-2 h-2 bg-orange-400 rounded-full"></div>
                                            Chờ thanh toán
                                        </span>
                                        @break
                                    @case('paid')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-800 rounded-lg text-sm font-medium">
                                            <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                            Đã thanh toán
                                        </span>
                                        @break
                                    @case('failed')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-800 rounded-lg text-sm font-medium">
                                            <div class="w-2 h-2 bg-red-400 rounded-full"></div>
                                            Thanh toán thất bại
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-800 rounded-lg text-sm font-medium">
                                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Notes -->
                @if($order->notes)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Ghi chú đơn hàng</h3>
                    </div>
                    <p class="text-gray-600 leading-relaxed">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
            
            <!-- Right Column - Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 sticky top-8">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Tổng kết đơn hàng</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tạm tính:</span>
                                <span class="font-medium text-gray-900">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phí vận chuyển:</span>
                                @if($order->shipping_fee > 0)
                                    <span class="font-medium text-gray-900">{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</span>
                                @else
                                    <span class="font-medium text-green-600">Miễn phí</span>
                                @endif
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold text-gray-900">Tổng cộng:</span>
                                    <span class="text-2xl font-bold text-blue-600">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="mt-8 space-y-3">
                            <a href="{{ route('profile.orders') }}" 
                               class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl font-medium transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Quay lại danh sách
                            </a>
                            
                            @if($order->status === 'pending' && $order->payment_status === 'pending')
                                <button type="button" 
                                        class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-colors"
                                        onclick="showCancelModal()">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Hủy đơn hàng
                                </button>
                            @endif
                            
                            @if($order->status === 'pending' && $order->payment_status === 'pending' && $order->payment_method === 'vnpay')
                                <a href="{{ route('payment.vnpay', $order->id) }}" 
                                   class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    Thanh toán ngay
                                </a>
                            @endif

                            @if($order->status === 'canceled')
                                <div class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-red-50 text-red-600 rounded-xl font-medium border border-red-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.982 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    Đơn hàng đã được hủy
                                </div>
                            @endif

                            @if($order->status === 'delivered')
                                <div class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-green-50 text-green-600 rounded-xl font-medium border border-green-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Đơn hàng đã hoàn thành
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cancel Order Modal -->
@if($order->status === 'pending' && $order->payment_status === 'pending')
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center" style="z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.982 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Hủy đơn hàng</h3>
                    <p class="text-sm text-gray-600">Đơn hàng #{{ $order->id }}</p>
                </div>
            </div>
            <p class="text-gray-600 mb-6">Bạn có chắc chắn muốn hủy đơn hàng này? Hành động này không thể hoàn tác.</p>
            <div class="flex gap-3">
                <button type="button" 
                        onclick="hideCancelModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                    Đóng
                </button>
                <form method="POST" action="{{ route('profile.order.cancel', $order->id) }}" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">
                        Xác nhận hủy
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Modal specific styles to prevent conflicts */
#cancelModal.flex {
    display: flex !important;
}
#cancelModal.hidden {
    display: none !important;
}
.overflow-hidden {
    overflow: hidden !important;
}
</style>

<script>
function showCancelModal() {
    console.log('showCancelModal called'); // Debug log
    const modal = document.getElementById('cancelModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
    console.log('Modal should be visible now'); // Debug log
}

function hideCancelModal() {
    console.log('hideCancelModal called'); // Debug log
    const modal = document.getElementById('cancelModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('cancelModal');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                hideCancelModal();
            }
        });
    }
});

// Close modal when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        hideCancelModal();
    }
});
</script>
@endif
@endsection