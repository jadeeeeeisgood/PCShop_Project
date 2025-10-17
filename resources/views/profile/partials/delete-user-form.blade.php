<section class="space-y-6">
    <button onclick="document.getElementById('confirm-modal').classList.remove('hidden')"
        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
        Xóa tài khoản
    </button>

    <!-- Delete Modal -->
    <div id="confirm-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Xóa tài khoản</h3>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-6">
                @csrf
                @method('delete')

                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-sm text-red-800">
                        <strong>⚠️ Cảnh báo:</strong> Hành động này không thể hoàn tác. Tất cả dữ liệu liên quan sẽ bị
                        xóa vĩnh viễn, bao gồm đơn hàng, giỏ hàng và thông tin cá nhân.
                    </p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Xác nhận bằng mật
                        khẩu</label>
                    <input id="password" name="password" type="password" placeholder="Nhập mật khẩu của bạn"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                    @if ($errors->userDeletion->has('password'))
                        <p class="text-red-600 text-sm mt-1">{{ $errors->userDeletion->first('password') }}</p>
                    @endif
                </div>

                <div class="flex gap-4">
                    <button type="button" onclick="document.getElementById('confirm-modal').classList.add('hidden')"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 px-4 py-3 rounded-lg font-medium transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition-colors">
                        Xóa tài khoản
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>