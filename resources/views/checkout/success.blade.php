@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Đặt hàng thành công') }}
    </h2>
@endsection

@section('content')
    <div class="py-12 sm:py-16">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 sm:p-12 text-center">
                    <!-- Success Icon -->
                    <div
                        class="mx-auto flex items-center justify-center h-20 w-20 sm:h-24 sm:w-24 rounded-full bg-green-100 mb-6">
                        <svg class="h-12 w-12 sm:h-14 sm:w-14 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>

                    <!-- Success Message -->
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">
                        Cảm ơn bạn đã đặt hàng!
                    </h1>

                    <p class="text-lg text-gray-600 mb-2">
                        Đơn hàng của bạn đã được đặt thành công.
                    </p>

                    <p class="text-gray-500 mb-8">
                        Bạn sẽ nhận được email xác nhận tại <strong>{{ $order->customer_email }}</strong> với chi tiết đơn hàng.
                    </p>

                    <!-- Order Details -->
                    <div class="bg-gray-50 rounded-lg p-4 sm:p-6 mb-8 text-left">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tóm tắt đơn hàng</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Mã đơn hàng:</span>
                                <span class="font-medium">#{{ $order->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tổng tiền:</span>
                                <span
                                    class="font-bold text-green-600">{{ number_format($order->total_amount, 0, ',', '.') }}
                                    VNĐ</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phương thức thanh toán:</span>
                                <span class="font-medium">
                                    @if($order->payment_method == 'cod')
                                        Thanh toán khi nhận hàng
                                    @elseif($order->payment_method == 'bank_transfer')
                                        Chuyển khoản ngân hàng
                                    @else
                                        {{ ucfirst($order->payment_method) }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Trạng thái:</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    @if($order->status == 'pending')
                                        Đang xử lý
                                    @elseif($order->status == 'processing')
                                        Đang chuẩn bị
                                    @elseif($order->status == 'shipped')
                                        Đã giao vận
                                    @elseif($order->status == 'delivered')
                                        Đã giao hàng
                                    @elseif($order->status == 'cancelled')
                                        Đã hủy
                                    @else
                                        {{ ucfirst($order->status) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('products.index') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-md transition duration-200 text-center">
                            Tiếp tục mua sắm
                        </a>
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-md transition duration-200 text-center">
                                Xem bảng điều khiển
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection