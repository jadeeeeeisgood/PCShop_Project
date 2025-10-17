@extends('layouts.admin')

@section('title', 'Chi tiết người dùng')

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h4 class="text-2xl font-semibold text-gray-900 dark:text-white">Chi tiết người dùng</h4>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Thông tin chi tiết của {{ $user->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Chỉnh sửa
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Avatar & Basic Info -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <div class="p-6 text-center">
                    <div class="mx-auto w-24 h-24 mb-4">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center text-2xl font-bold text-white {{ $user->role === 'admin' ? 'bg-red-500' : 'bg-blue-500' }}">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    </div>
                    <h5 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h5>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $user->email }}</p>
                    
                    <div class="flex justify-center space-x-2 mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $user->role === 'admin' ? 'Admin' : 'Khách hàng' }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                        </span>
                    </div>

                    <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex justify-between">
                            <span>ID:</span>
                            <span class="font-medium">#{{ $user->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ngày tạo:</span>
                            <span class="font-medium">{{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Lần cuối cập nhật:</span>
                            <span class="font-medium">{{ $user->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Thao tác nhanh</h5>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.users.edit', $user) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Chỉnh sửa thông tin
                        </a>
                        
                        @if($user->role === 'customer')
                        <a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Xem đơn hàng
                        </a>
                        @endif
                        
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Xóa tài khoản
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="lg:col-span-2">
            <!-- Personal Information -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Thông tin cá nhân</h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Họ và tên</label>
                            <p class="text-base text-gray-900 dark:text-white mt-1">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                            <p class="text-base text-gray-900 dark:text-white mt-1">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Số điện thoại</label>
                            <p class="text-base text-gray-900 dark:text-white mt-1">{{ $user->phone ?: 'Chưa cập nhật' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Vai trò</label>
                            <p class="text-base text-gray-900 dark:text-white mt-1">{{ $user->role === 'admin' ? 'Quản trị viên' : 'Khách hàng' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Địa chỉ</label>
                            <p class="text-base text-gray-900 dark:text-white mt-1">{{ $user->address ?: 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Statistics -->
            @if($user->role === 'customer')
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Thống kê hoạt động</h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $user->orders->count() }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Tổng đơn hàng</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($user->orders->sum('total'), 0, ',', '.') }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Tổng chi tiêu (VNĐ)</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $user->orders->where('status', 'delivered')->count() }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Đơn hoàn thành</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            @if($user->orders->count() > 0)
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Đơn hàng gần đây</h5>
                        <a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}" class="text-sm text-blue-600 hover:text-blue-500">
                            Xem tất cả
                        </a>
                    </div>
                </div>
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Đơn hàng</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ngày đặt</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tổng tiền</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($user->orders->take(5) as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">#{{ $order->id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $order->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($order->total, 0, ',', '.') }} VNĐ</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                        Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            @endif

            <!-- Account History -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Lịch sử tài khoản</h5>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900 dark:text-white font-medium">Tài khoản được tạo</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($user->email_verified_at)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.94 6.412A2 2 0 002 8.108V16a2 2 0 002 2h12a2 2 0 002-2V8.108a2 2 0 00-.94-1.696l-6-3.75a2 2 0 00-2.12 0l-6 3.75zm3.1 2.236L12 12l5.96-3.352a1 1 0 00.04-1.851L12 3.604 6 6.797a1 1 0 00.04 1.851z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900 dark:text-white font-medium">Email đã được xác thực</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email_verified_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900 dark:text-white font-medium">Lần cập nhật cuối</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection