<!-- Header -->
<header class="fixed left-0 top-0 w-full z-40 bg-white transition-all ease-in-out duration-300 shadow-sm"
    x-data="{ stickyMenu: false, navigationOpen: false }" x-init="
        window.addEventListener('scroll', () => {
            stickyMenu = window.scrollY >= 80;
        });
    " :class="{ 'shadow-lg': stickyMenu }">
    <div class="max-w-[1170px] mx-auto px-4 sm:px-8 xl:px-0">

        <!-- Header Top -->
        <div
            class="flex flex-col lg:flex-row gap-5 items-end lg:items-center xl:justify-between ease-out duration-200 py-4 lg:py-6">

            <!-- Header Top Left -->
            <div class="xl:w-auto flex-col sm:flex-row w-full flex sm:justify-between sm:items-center gap-5 sm:gap-10">

                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="flex-shrink-0">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">PC</span>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">PC Shop</span>
                    </div>
                </a>

                <!-- Search Bar -->
                <div class="max-w-[475px] w-full">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div
                            class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">

                            <!-- Category Dropdown -->
                            <div class="relative border-r border-gray-200">
                                <select name="category"
                                    class="appearance-none bg-transparent border-0 pl-4 pr-8 py-3 text-sm font-medium text-gray-700 focus:outline-none focus:ring-0 min-w-[120px] h-full">
                                    <option value="">Tất cả</option>
                                    @foreach(\App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}" {{ (string) request('category') == (string) $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Search Input -->
                            <div class="relative flex-1">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Tìm kiếm sản phẩm..."
                                    class="block w-full pl-4 pr-4 py-3 border-0 bg-transparent text-sm placeholder-gray-500 focus:outline-none focus:ring-0 h-full">
                            </div>

                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 transition-colors flex items-center gap-2 h-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span class="hidden sm:inline">Tìm kiếm</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Header Top Right -->
            <div class="flex items-center gap-6">

                <!-- Contact Info -->
                <div class="hidden lg:flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span class="text-gray-600">(+84) 123-456-789</span>
                    </div>
                </div>

                <!-- Cart -->
                <div class="relative">
                    <button class="flex items-center gap-3 text-gray-700 hover:text-blue-600 transition-colors"
                        onclick="window.location.href='{{ route('cart.index') }}'">
                        <span class="relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9m-9 0l1.5-6m7.5 6v0" />
                            </svg>
                            <span
                                class="cart-counter absolute -right-2 -top-2 bg-blue-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                        </span>
                        <div class="hidden sm:block">
                            <span class="block text-xs text-gray-500 uppercase">Giỏ hàng</span>
                            <p class="font-medium text-sm text-gray-900">0 VNĐ</p>
                        </div>
                    </button>
                </div>

                <!-- User Menu -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-3 text-gray-700 hover:text-blue-600 transition-colors">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <div class="hidden sm:block text-left">
                                <span class="block text-xs text-gray-500">Xin chào</span>
                                <p class="font-medium text-sm text-gray-900">{{ auth()->user()->name }}</p>
                            </div>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Hồ sơ của tôi
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Đơn hàng của tôi
                            </a>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Admin Dashboard
                                </a>
                            @endif
                            <hr class="my-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 text-sm font-medium">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            Đăng ký
                        </a>
                    </div>
                @endauth

                <!-- Mobile Menu Toggle -->
                <button @click="navigationOpen = !navigationOpen" class="xl:hidden block p-2" aria-label="Toggle Menu">
                    <span class="block relative w-6 h-6">
                        <span class="block absolute h-0.5 w-full bg-gray-600 transform transition duration-300"
                            :class="navigationOpen ? 'rotate-45 top-3' : 'top-1'"></span>
                        <span class="block absolute h-0.5 w-full bg-gray-600 top-3 transition duration-300"
                            :class="navigationOpen ? 'opacity-0' : 'opacity-100'"></span>
                        <span class="block absolute h-0.5 w-full bg-gray-600 transform transition duration-300"
                            :class="navigationOpen ? '-rotate-45 top-3' : 'top-5'"></span>
                    </span>
                </button>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="border-t border-gray-200">
            <div class="flex items-center justify-between py-4">

                <!-- Desktop Navigation -->
                <nav class="hidden xl:flex items-center space-x-8">
                    <a href="{{ route('welcome') }}"
                        class="flex items-center gap-2 text-gray-700 hover:text-blue-600 font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Trang chủ
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                        Sản phẩm
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                        Về chúng tôi
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                        Liên hệ
                    </a>
                </nav>

                <!-- Special Offers -->
                <div class="hidden lg:flex items-center gap-6">
                    <div class="flex items-center gap-2 text-sm">
                        <span class="text-red-600 font-medium">🔥 HOT DEAL:</span>
                        <span class="text-gray-600">Giảm giá tới 50% cho Gaming PC</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="navigationOpen" x-transition class="xl:hidden border-t border-gray-200">
            <nav class="py-4 space-y-2">
                <a href="{{ route('welcome') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                    Trang chủ
                </a>
                <a href="{{ route('products.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                    Sản phẩm
                </a>
                <div class="px-4 py-2">
                    <div class="text-gray-700 font-medium mb-2">Danh mục</div>
                    <div class="pl-4 space-y-1">
                        @foreach(\App\Models\Category::take(6)->get() as $category)
                            <a href="{{ route('products.index', ['category' => $category->id]) }}"
                                class="block py-1 text-sm text-gray-600 hover:text-blue-600">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                    Về chúng tôi
                </a>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                    Liên hệ
                </a>
            </nav>
        </div>
    </div>
</header>