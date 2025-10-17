@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - PC Shop')

@section('content')
    <!-- Breadcrumb Section -->
    <section class="bg-gray-50 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-600" aria-label="Breadcrumb">
                <a href="{{ route('welcome') }}" class="hover:text-blue-600 transition-colors whitespace-nowrap">Trang chủ</a>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors whitespace-nowrap">Tài khoản</a>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900 font-medium whitespace-nowrap">Đơn hàng</span>
            </nav>
        </div>
    </section>

    <!-- Orders Section -->
    <section class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar -->
                <div class="lg:w-1/4 xl:w-1/5">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                        <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-700">
                            <h2 class="text-lg font-semibold text-white">Tài khoản</h2>
                            <p class="text-blue-100 text-sm mt-1">Quản lý thông tin cá nhân</p>
                        </div>
                        <nav class="p-4">
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v4M16 5v4" />
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('profile.edit') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Chỉnh sửa hồ sơ
                            </a>
                            <a href="{{ route('profile.orders') }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('profile.orders') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }} transition-all duration-200 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Đơn hàng
                            </a>
                            <div class="border-t border-gray-200 mt-4 pt-4">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition-all duration-200 text-left">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:w-3/4 xl:w-4/5">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-900">Đơn hàng của tôi</h2>
                                    <p class="text-sm text-gray-600">Quản lý và theo dõi tất cả đơn hàng của bạn</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($orders->count() > 0)
                                <div class="space-y-4">
                                    @foreach($orders as $order)
                                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow bg-white">
                                            <!-- Order Header -->
                                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                                <div class="flex flex-wrap items-center justify-between gap-4">
                                                    <div class="flex items-center gap-6">
                                                        <div>
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">Mã đơn</p>
                                                            <p class="text-sm font-bold text-gray-900">#{{ $order->order_number ?? $order->id }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">Ngày đặt</p>
                                                            <p class="text-sm text-gray-700">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">Trạng thái</p>
                                                            @php
                                                                $statusClasses = [
                                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                                    'processing' => 'bg-blue-100 text-blue-800', 
                                                                    'shipped' => 'bg-purple-100 text-purple-800',
                                                                    'delivered' => 'bg-green-100 text-green-800',
                                                                    'cancelled' => 'bg-red-100 text-red-800'
                                                                ];
                                                                $statusLabels = [
                                                                    'pending' => 'Chờ xử lý',
                                                                    'processing' => 'Đang xử lý',
                                                                    'shipped' => 'Đã gửi hàng',
                                                                    'delivered' => 'Đã giao hàng',
                                                                    'cancelled' => 'Đã hủy'
                                                                ];
                                                            @endphp
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-3">
                                                        <div class="text-right">
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">Tổng cộng</p>
                                                            <p class="text-lg font-bold text-blue-600">{{ number_format($order->total_amount, 0, ',', '.') }}đ</p>
                                                        </div>
                                                        <div class="flex gap-2">
                                                            <a href="{{ route('profile.order', $order->id) }}" 
                                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
                                                                Chi tiết
                                                            </a>
                                                            @if($order->status === 'pending')
                                                                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
                                                                    Hủy
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Order Items -->
                                            <div class="p-4">
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                    @foreach($order->orderItems->take(3) as $item)
                                                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                                            @if($item->product && $item->product->image)
                                                                <img src="{{ $item->product->image_url }}" 
                                                                     alt="{{ $item->product->name }}" 
                                                                     class="w-12 h-12 rounded object-cover flex-shrink-0">
                                                            @else
                                                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product ? $item->product->name : $item->product_name }}</p>
                                                                <p class="text-xs text-gray-600">SL: {{ $item->quantity }} × {{ number_format($item->price, 0, ',', '.') }}đ</p>
                                                                <p class="text-sm font-semibold text-blue-600">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @if($order->orderItems->count() > 3)
                                                    <p class="text-sm text-gray-600 mt-3 text-center">
                                                        <span class="bg-gray-100 px-3 py-1 rounded-full">+{{ $order->orderItems->count() - 3 }} sản phẩm khác</span>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Pagination -->
                                @if($orders->hasPages())
                                    <div class="mt-8">
                                        {{ $orders->links() }}
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Bạn chưa có đơn hàng nào</h3>
                                    <p class="text-gray-600 mb-6">Hãy bắt đầu mua sắm để tạo đơn hàng đầu tiên của bạn</p>
                                    <a href="{{ route('products.index') }}" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                        Mua sắm ngay
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection