<!-- Quick View Modal -->
<div id="quick-view-modal" class="fixed inset-0 z-50 hidden" x-data="{ open: false }" x-show="open"
    @quick-view-toggle.window="open = !open">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="open = false"></div>

    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Xem nhanh sản phẩm</h2>
                <button @click="open = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div id="quick-view-content" class="p-6">
                <!-- Content will be loaded here -->
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-4 text-gray-600">Đang tải...</p>
                </div>
            </div>
        </div>
    </div>
</div>