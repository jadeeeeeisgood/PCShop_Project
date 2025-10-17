@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
    <!-- Header with Navigation -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Quay lại danh sách
                    </a>
                    <nav class="flex space-x-1 text-sm text-gray-500">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700">Trang chủ</a>
                        <span>/</span>
                        <a href="{{ route('admin.orders.index') }}" class="hover:text-gray-700">Đơn hàng</a>
                        <span>/</span>
                        <span class="text-gray-900 font-medium">Chi tiết #{{ $order->id }}</span>
                    </nav>
                </div>
                <div class="flex items-center space-x-3">
                    @if($order->status !== 'delivered' && $order->status !== 'canceled')
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Cập nhật trạng thái
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 z-10 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    @if($order->status == 'pending')
                                        <button onclick="updateOrderStatus({{ $order->id }}, 'processing')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Bắt đầu xử lý
                                        </button>
                                    @endif
                                    @if($order->status == 'processing')
                                        <button onclick="updateOrderStatus({{ $order->id }}, 'shipped')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Đánh dấu đã gửi
                                        </button>
                                    @endif
                                    @if($order->status == 'shipped')
                                        <button onclick="updateOrderStatus({{ $order->id }}, 'delivered')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Hoàn thành đơn hàng
                                        </button>
                                    @endif
                                    @if(in_array($order->status, ['pending', 'processing']))
                                        <hr class="my-1 border-gray-200">
                                        <button onclick="updateOrderStatus({{ $order->id }}, 'canceled')" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100">
                                            Hủy đơn hàng
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Order Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Đơn hàng #{{ $order->id }}</h1>
                            <p class="text-sm text-gray-600 mt-1">Đặt hàng vào {{ $order->created_at->format('d/m/Y \l\ú\c H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center space-x-4">
                                <!-- Status Badge -->
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'shipped' => 'bg-purple-100 text-purple-800',
                                        'delivered' => 'bg-green-100 text-green-800',
                                        'canceled' => 'bg-red-100 text-red-800'
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Chờ xử lý',
                                        'processing' => 'Đang xử lý',
                                        'shipped' => 'Đã gửi hàng',
                                        'delivered' => 'Đã giao hàng',
                                        'canceled' => 'Đã hủy'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    <svg class="w-2 h-2 mr-2 fill-current" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                </span>
                                
                                <!-- Total Amount -->
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Tổng cộng</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ number_format($order->total, 0, ',', '.') }} VNĐ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Customer Information -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Thông tin khách hàng
                            </h3>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Họ và tên</label>
                                <p class="text-base text-gray-900 mt-1">{{ $order->customer_name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email</label>
                                <p class="text-base text-gray-900 mt-1">{{ $order->customer_email }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Địa chỉ giao hàng</label>
                                <p class="text-base text-gray-900 mt-1">{{ $order->customer_address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-6">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Thông tin thanh toán
                            </h3>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            @php
                                $paymentMethods = [
                                    'cod' => 'Thu hộ (COD)',
                                    'bank_transfer' => 'Chuyển khoản ngân hàng',
                                    'credit_card' => 'Thẻ tín dụng',
                                    'e_wallet' => 'Ví điện tử'
                                ];
                                $paymentStatuses = [
                                    'pending' => 'Chờ thanh toán',
                                    'paid' => 'Đã thanh toán',
                                    'failed' => 'Thất bại',
                                    'refunded' => 'Đã hoàn tiền'
                                ];
                                $paymentStatusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                    'refunded' => 'bg-blue-100 text-blue-800'
                                ];
                            @endphp
                            <div>
                                <label class="text-sm font-medium text-gray-500">Phương thức</label>
                                <p class="text-base text-gray-900 mt-1">{{ $paymentMethods[$order->payment_method] ?? $order->payment_method }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Trạng thái</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1 {{ $paymentStatusClasses[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $paymentStatuses[$order->payment_status] ?? $order->payment_status }}
                                </span>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tổng tiền</label>
                                <p class="text-xl font-bold text-blue-600 mt-1">{{ number_format($order->total, 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                Sản phẩm đã đặt
                                <span class="ml-2 text-sm bg-gray-200 text-gray-700 px-2 py-1 rounded-full">{{ $order->orderItems->count() }} sản phẩm</span>
                            </h3>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-base font-semibold text-gray-900">{{ $item->product->name }}</h4>
                                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                    </svg>
                                                    Đơn giá: {{ number_format($item->price, 0, ',', '.') }} VNĐ
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                                    </svg>
                                                    Số lượng: {{ $item->quantity }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-right ml-4">
                                            <p class="text-lg font-bold text-gray-900">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- Order Summary -->
                            <div class="px-6 py-4 bg-gray-50">
                                <div class="space-y-2">
                                    @php
                                        $subtotal = $order->orderItems->sum(function($item) {
                                            return $item->price * $item->quantity;
                                        });
                                        $shipping = 0; // Có thể thêm phí ship nếu cần
                                        $discount = 0; // Có thể thêm giảm giá nếu cần
                                    @endphp
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Tạm tính:</span>
                                        <span>{{ number_format($subtotal, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                    @if($shipping > 0)
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Phí vận chuyển:</span>
                                        <span>{{ number_format($shipping, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                    @endif
                                    @if($discount > 0)
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Giảm giá:</span>
                                        <span class="text-red-600">-{{ number_format($discount, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                    @endif
                                    <hr class="border-gray-300">
                                    <div class="flex justify-between text-lg font-bold text-gray-900">
                                        <span>Tổng cộng:</span>
                                        <span class="text-blue-600">{{ number_format($order->total, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update JavaScript -->
    <script>
        function updateOrderStatus(orderId, status) {
            if (confirm('Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng này?')) {
                // Create form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/orders/${orderId}`;
                form.style.display = 'none';

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Add method override
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);

                // Add status
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = status;
                form.appendChild(statusInput);

                // Submit form
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection
