<!-- Cart Sidebar -->
<div id="cart-sidebar"
    class="fixed inset-y-0 right-0 z-50 w-96 bg-white shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out"
    x-data="{ open: false }" x-show="open" @cart-sidebar-toggle.window="open = !open"
    x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full"
    x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300"
    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Giỏ hàng</h2>
            <button @click="open = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-6">
            <div id="cart-items" class="space-y-4">
                <!-- Cart items will be loaded here -->
                <div class="text-center text-gray-500 py-8">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9m-9 0l1.5-6m7.5 6v0" />
                    </svg>
                    <p>Giỏ hàng trống</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-600">Tổng cộng:</span>
                <span id="cart-total" class="text-xl font-bold text-gray-900">0 VNĐ</span>
            </div>

            <div class="space-y-3">
                <a href="{{ route('cart.index') }}"
                    class="block w-full bg-gray-100 text-gray-900 text-center py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                    Xem giỏ hàng
                </a>
                <a href="{{ route('checkout.index') }}"
                    class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    Thanh toán
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Cart Sidebar Overlay -->
<div x-show="open" @click="open = false" x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 z-40"></div>