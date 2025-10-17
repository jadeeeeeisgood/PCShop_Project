<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PC Shop - Cửa hàng máy tính hàng đầu')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Product Filters CSS -->
    <link rel="stylesheet" href="{{ asset('css/product-filters.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Product Filters JS -->
    <script src="{{ asset('js/product-filters.js') }}"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #3B82F6;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #2563EB;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Fixed header compensation */
        main {
            padding-top: 200px !important;
            margin-top: 0 !important;
        }

        /* Hero carousel styles */
        .hero-carousel .swiper-pagination-bullet {
            background: rgba(255, 255, 255, 0.5);
            opacity: 1;
        }

        .hero-carousel .swiper-pagination-bullet-active {
            background: #3B82F6;
        }

        /* Force remove blocking overlays */
        .fixed.inset-0:not(header):not(.back-to-top) {
            display: none !important;
        }

        [class*="z-5"]:not(header):not(.back-to-top) {
            pointer-events: none !important;
        }

        /* Ensure header is clickable */
        header,
        header * {
            pointer-events: auto !important;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    @include('components.header')

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="relative">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <!-- Footer -->
    @include('components.footer')

    <!-- Back to Top Button -->
    <button id="back-to-top"
        class="fixed bottom-8 right-8 w-12 h-12 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition-all duration-300 opacity-0 invisible z-30"
        onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        // Back to top button
        window.addEventListener('scroll', function () {
            const backToTop = document.getElementById('back-to-top');
            if (window.scrollY > 300) {
                backToTop.classList.remove('opacity-0', 'invisible');
                backToTop.classList.add('opacity-100', 'visible');
            } else {
                backToTop.classList.add('opacity-0', 'invisible');
                backToTop.classList.remove('opacity-100', 'visible');
            }
        });

        // Cart functionality
        function addToCart(productId) {
            const url = `/cart/add/${productId}`;
            console.log('Adding to cart:', productId, 'URL:', url);

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        // Update cart counter
                        const cartCounter = document.querySelector('.cart-counter');
                        if (cartCounter && data.cartCount) {
                            cartCounter.textContent = data.cartCount;
                            cartCounter.classList.remove('opacity-0');
                        }

                        // Show success message
                        showNotification(data.message || 'Đã thêm sản phẩm vào giỏ hàng!', 'success');
                    } else {
                        showNotification(data.message || 'Có lỗi xảy ra!', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Có lỗi xảy ra!', 'error');
                });
        }

        // Notification system
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full ${type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'
                }`;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Update cart count on page load
        fetch('{{ route("cart.count") }}')
            .then(response => response.json())
            .then(data => {
                const cartCounter = document.querySelector('.cart-counter');
                if (cartCounter && data.count > 0) {
                    cartCounter.textContent = data.count;
                    cartCounter.classList.remove('opacity-0');
                }
            })
            .catch(error => console.error('Error:', error));
    </script>

    <!-- Pusher Configuration -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        // Global Pusher configuration
        window.pusherConfig = {
            key: '{{ config("services.pusher.key") }}',
            cluster: '{{ config("services.pusher.options.cluster") }}'
        };
    </script>

    <!-- Stock Updates Script -->
    <script src="{{ asset('js/stock-updates.js') }}"></script>

    @stack('scripts')
</body>

</html>