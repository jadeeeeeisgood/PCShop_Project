@extends('layouts.app')

@section('title', 'Chỉnh sửa hồ sơ - PC Shop')

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
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors whitespace-nowrap">Tài
                    khoản</a>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900 font-medium whitespace-nowrap">Chỉnh sửa hồ sơ</span>
            </nav>
        </div>
    </section>

    <!-- Profile Edit Section -->
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
                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 5v4M16 5v4" />
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg bg-blue-50 text-blue-600 border-r-2 border-blue-600 transition-all duration-200 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Chỉnh sửa hồ sơ
                            </a>
                            <a href="{{ route('profile.orders') }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200 mb-1">
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
                <div class="lg:w-3/4 xl:w-4/5 space-y-6">
                    <!-- Update Profile Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-transparent">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Thông tin cá nhân</h3>
                                    <p class="text-sm text-gray-600">Cập nhật tên, email và thông tin liên hệ</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Update Password -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-transparent">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Đổi mật khẩu</h3>
                                    <p class="text-sm text-gray-600">Đảm bảo tài khoản của bạn được bảo mật</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-transparent">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Xóa tài khoản</h3>
                                    <p class="text-sm text-gray-600">Xóa vĩnh viễn tài khoản và tất cả dữ liệu</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="flex items-center gap-3 mb-2">
        <div class="p-3 bg-amber-100 rounded-lg">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Đổi mật khẩu</h3>
            <p class="text-sm text-gray-600">Cập nhật mật khẩu của bạn để bảo vệ tài khoản</p>
        </div>
    </div>
    </div>
    <div class="p-6">
        @include('profile.partials.update-password-form')
    </div>
    </div>

    <!-- Delete Account -->
    <div class="bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden">
        <div class="p-6 border-b border-red-200 bg-gradient-to-r from-red-50 to-transparent">
            <div class="flex items-center gap-3 mb-2">
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Xóa tài khoản</h3>
                    <p class="text-sm text-gray-600">Xóa vĩnh viễn tài khoản và tất cả dữ liệu liên quan</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
    </div>
    </div>
    </div>
    </section>
@endsection