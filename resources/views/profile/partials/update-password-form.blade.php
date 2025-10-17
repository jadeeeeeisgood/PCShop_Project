<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-2">Mật
                    khẩu hiện tại</label>
                <input id="update_password_current_password" name="current_password" type="password"
                    autocomplete="current-password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                @if ($errors->updatePassword->has('current_password'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->updatePassword->first('current_password') }}</p>
                @endif
            </div>

            <div class="md:row-span-2 flex flex-col justify-center">
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-amber-800 mb-2">Lưu ý bảo mật:</h4>
                    <ul class="text-xs text-amber-700 space-y-1">
                        <li>• Mật khẩu tối thiểu 8 ký tự</li>
                        <li>• Nên bao gồm chữ hoa, thường, số</li>
                        <li>• Không chia sẻ mật khẩu với ai</li>
                        <li>• Thay đổi định kỳ để bảo mật</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu
                    mới</label>
                <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                @if ($errors->updatePassword->has('password'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->updatePassword->first('password') }}</p>
                @endif
            </div>

            <div>
                <label for="update_password_password_confirmation"
                    class="block text-sm font-medium text-gray-700 mb-2">Xác nhận mật khẩu</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    autocomplete="new-password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                @if ($errors->updatePassword->has('password_confirmation'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
            <button type="submit"
                class="bg-amber-600 hover:bg-amber-700 text-white px-8 py-3 rounded-lg font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Cập nhật mật khẩu
            </button>

            @if (session('status') === 'password-updated')
                <div class="text-sm text-green-700 font-medium">✓ Mật khẩu được cập nhật</div>
            @endif
        </div>
    </form>
</section>