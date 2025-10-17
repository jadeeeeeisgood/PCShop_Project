<!-- Newsletter Modal -->
<div id="newsletter-modal" class="fixed inset-0 z-50 hidden" x-data="{ open: false }" x-show="open"
    @newsletter-toggle.window="open = !open">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="open = false"></div>

    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-md w-full" @click.stop>
            <!-- Modal Header -->
            <div class="text-center p-6 border-b border-gray-200">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Đăng ký nhận tin</h2>
                <p class="text-gray-600 text-sm">Nhận thông tin về sản phẩm mới và ưu đãi đặc biệt</p>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form class="space-y-4">
                    <div>
                        <label for="newsletter-email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email của bạn
                        </label>
                        <input type="email" id="newsletter-email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                            placeholder="example@email.com">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="newsletter-agree"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="newsletter-agree" class="ml-2 block text-sm text-gray-700">
                            Tôi đồng ý nhận email marketing từ PC Shop
                        </label>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="open = false"
                            class="flex-1 bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                            Để sau
                        </button>
                        <button type="submit"
                            class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            Đăng ký
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>