<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Họ và tên</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus
                    autocomplete="name"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                @if ($errors->has('name'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->first('name') }}</p>
                @endif
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                    autocomplete="username"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                @if ($errors->has('email'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->first('email') }}</p>
                @endif
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    ⚠️ <strong>Email của bạn chưa được xác minh.</strong>
                    <button form="send-verification"
                        class="ml-2 text-yellow-900 underline hover:text-yellow-700 font-medium">
                        Nhấp để gửi lại email xác minh
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm text-green-700 font-medium">
                        ✓ Email xác minh mới đã được gửi đến địa chỉ của bạn.
                    </p>
                @endif
            </div>
        @endif

        <!-- Additional Profile Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" autocomplete="tel"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    placeholder="Nhập số điện thoại">
                @if ($errors->has('phone'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->first('phone') }}</p>
                @endif
            </div>

            <div>
                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Ngày sinh</label>
                <input id="birth_date" name="birth_date" type="date" value="{{ old('birth_date', $user->birth_date) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                @if ($errors->has('birth_date'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->first('birth_date') }}</p>
                @endif
            </div>
        </div>

        <div>
            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
            <textarea id="address" name="address" rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                placeholder="Nhập địa chỉ đầy đủ">{{ old('address', $user->address) }}</textarea>
            @if ($errors->has('address'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->first('address') }}</p>
            @endif
        </div>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Lưu thay đổi
            </button>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-700 font-medium">✓ Hồ sơ được cập nhật thành công</p>
            </div>
        @endif
    </form>
</section>