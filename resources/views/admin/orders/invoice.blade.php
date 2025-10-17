@extends('layouts.admin')

@section('title', 'Hóa đơn #' . $order->id)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
        <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 print:hidden">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Hóa đơn #{{ $order->id }}</h3>
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                In hóa đơn
            </button>
        </div>
        
        <div class="p-6 invoice-content">
            <!-- Company Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">PC Shop</h4>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        123 Đường ABC, Quận 1<br>
                        TP. Hồ Chí Minh<br>
                        Phone: +84 123 456 789<br>
                        Email: info@pcshop.com
                    </div>
                </div>
                <div class="text-right">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">HÓA ĐƠN</h4>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <div class="mb-1"><strong>Số hóa đơn:</strong> #{{ $order->id }}</div>
                        <div class="mb-1"><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</div>
                        <div class="mb-1">
                            <strong>Trạng thái:</strong> 
                            @switch($order->status)
                                @case('pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Chờ xử lý</span>
                                    @break
                                @case('processing')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Đang xử lý</span>
                                    @break
                                @case('shipped')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Đã gửi</span>
                                    @break
                                @case('delivered')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Đã giao</span>
                                    @break
                                @case('canceled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Đã hủy</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer & Payment Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h5 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Thông tin khách hàng</h5>
                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <div><strong>Tên:</strong> {{ $order->customer_name }}</div>
                        <div><strong>Email:</strong> {{ $order->customer_email }}</div>
                        <div><strong>Địa chỉ:</strong> {{ $order->customer_address }}</div>
                    </div>
                </div>
                <div>
                    <h5 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Thông tin thanh toán</h5>
                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <div>
                            <strong>Phương thức:</strong> 
                            @switch($order->payment_method)
                                @case('cod')
                                    Thu hộ (COD)
                                    @break
                                @case('bank_transfer')
                                    Chuyển khoản ngân hàng
                                    @break
                                @case('credit_card')
                                    Thẻ tín dụng
                                    @break
                                @default
                                    {{ $order->payment_method }}
                            @endswitch
                        </div>
                        <div>
                            <strong>Trạng thái thanh toán:</strong>
                            @if($order->payment_status === 'paid')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Đã thanh toán</span>
                            @elseif($order->payment_status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Chờ thanh toán</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Chưa thanh toán</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mb-8">
                <h5 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Chi tiết đơn hàng</h5>
                <div class="overflow-hidden border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sản phẩm</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Số lượng</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Đơn giá</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->product->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-white">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right text-sm text-gray-900 dark:text-white">{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                <td class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-white">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">Tổng cộng:</th>
                                <th class="px-6 py-3 text-right text-sm font-bold text-blue-600 dark:text-blue-400">{{ number_format($order->total, 0, ',', '.') }} VNĐ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center pt-8 border-t border-gray-200 dark:border-gray-700">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Cảm ơn bạn đã mua hàng tại PC Shop!<br>
                    Mọi thắc mắc xin liên hệ: +84 123 456 789 hoặc info@pcshop.com
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .print\\:hidden {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
    
    .invoice-content {
        padding: 0 !important;
    }
    
    .dark\\:bg-gray-800,
    .dark\\:bg-gray-700,
    .dark\\:border-gray-700 {
        background: white !important;
        border-color: #e5e7eb !important;
    }
    
    .dark\\:text-white,
    .dark\\:text-gray-300,
    .dark\\:text-gray-400 {
        color: #111827 !important;
    }
}
</style>
@endsection
