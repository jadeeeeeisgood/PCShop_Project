@extends('layouts.app')

@section('title', 'Tài khoản của tôi - PC Shop')

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
                <span class="text-gray-900 font-medium whitespace-nowrap">Tài khoản</span>
            </nav>
        </div>
    </section>

    <!-- Dashboard Section -->
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
                    <!-- Welcome Header -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xl font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">Xin chào, {{ auth()->user()->name }}!</h1>
                                    <p class="text-gray-600 mt-1">Chào mừng bạn trở lại trang quản lý tài khoản</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->orders->count() }}</p>
                                    <p class="text-sm text-gray-600">Tổng đơn hàng</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->orders->where('status', 'delivered')->count() }}</p>
                                    <p class="text-sm text-gray-600">Đã giao hàng</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->orders->whereIn('status', ['pending', 'processing'])->count() }}</p>
                                    <p class="text-sm text-gray-600">Đang xử lý</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-semibold text-gray-900">Thông tin cá nhân</h2>
                                        <p class="text-sm text-gray-600">Thông tin tài khoản và liên hệ</p>
                                    </div>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors inline-flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Chỉnh sửa
                                </a>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Họ và tên</label>
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                                            {{ auth()->user()->name }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                                            {{ auth()->user()->email }}
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                                            {{ auth()->user()->phone ?? 'Chưa cập nhật' }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngày sinh</label>
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                                            {{ auth()->user()->birth_date ? \Carbon\Carbon::parse(auth()->user()->birth_date)->format('d/m/Y') : 'Chưa cập nhật' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg min-h-[88px] flex items-center">
                                            {{ auth()->user()->address ?? 'Chưa cập nhật' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-semibold text-gray-900">Đơn hàng gần đây</h2>
                                        <p class="text-sm text-gray-600">5 đơn hàng mới nhất của bạn</p>
                                    </div>
                                </div>
                                @if(auth()->user()->orders->count() > 0)
                                    <a href="{{ route('profile.orders') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                        Xem tất cả
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            @if(auth()->user()->orders->count() > 0)
                                <div class="space-y-4">
                                    @foreach(auth()->user()->orders()->latest()->take(3)->get() as $order)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow bg-gray-50">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center gap-4">
                                                    <div>
                                                        <p class="font-semibold text-gray-900">#{{ $order->order_number ?? $order->id }}</p>
                                                        <p class="text-sm text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-3">
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
                                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                                    </span>
                                                    <span class="font-bold text-blue-600">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    @if($order->orderItems->count() > 0 && $order->orderItems->first()->product && $order->orderItems->first()->product->image)
                                                        <img src="{{ $order->orderItems->first()->product->image_url }}" 
                                                             alt="{{ $order->orderItems->first()->product->name }}" 
                                                             class="w-10 h-10 rounded object-cover">
                                                    @else
                                                        <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $order->orderItems->count() }} sản phẩm
                                                        </p>
                                                        @if($order->orderItems->count() > 1)
                                                            <p class="text-xs text-gray-600">và {{ $order->orderItems->count() - 1 }} sản phẩm khác</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <a href="{{ route('profile.order', $order->id) }}"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                                    Chi tiết
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if(auth()->user()->orders->count() > 3)
                                    <div class="text-center mt-6">
                                        <a href="{{ route('profile.orders') }}"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                            Xem tất cả đơn hàng ({{ auth()->user()->orders->count() }})
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Bạn chưa có đơn hàng nào</h3>
                                    <p class="text-gray-600 mb-6">Hãy bắt đầu mua sắm để xem lịch sử đơn hàng tại đây</p>
                                    <a href="{{ route('products.index') }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
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