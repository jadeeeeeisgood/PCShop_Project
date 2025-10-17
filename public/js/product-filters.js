// Product filter functionality
document.addEventListener('DOMContentLoaded', function () {
    // Mobile filter toggle
    const filterToggle = document.getElementById('filter-toggle');
    const filterSidebar = document.getElementById('filter-sidebar');
    const filterOverlay = document.getElementById('filter-overlay');
    const filterClose = document.getElementById('filter-close');

    if (filterToggle && filterSidebar) {
        filterToggle.addEventListener('click', function () {
            filterSidebar.classList.add('open');
            if (filterOverlay) {
                filterOverlay.classList.add('active');
            }
        });

        if (filterClose) {
            filterClose.addEventListener('click', closeFilter);
        }

        if (filterOverlay) {
            filterOverlay.addEventListener('click', closeFilter);
        }

        function closeFilter() {
            filterSidebar.classList.remove('open');
            if (filterOverlay) {
                filterOverlay.classList.remove('active');
            }
        }
    }

    // Handle price range radio buttons
    const priceRangeRadios = document.querySelectorAll('input[name="price_range"]');
    const minPriceInput = document.querySelector('input[name="min_price"]');
    const maxPriceInput = document.querySelector('input[name="max_price"]');

    if (priceRangeRadios.length > 0) {
        priceRangeRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.checked) {
                    // Clear custom price inputs when selecting predefined range
                    if (minPriceInput) minPriceInput.value = '';
                    if (maxPriceInput) maxPriceInput.value = '';
                }
            });
        });
    }

    // Clear price range when custom input is used
    if (minPriceInput || maxPriceInput) {
        [minPriceInput, maxPriceInput].forEach(input => {
            if (input) {
                input.addEventListener('input', function () {
                    priceRangeRadios.forEach(radio => {
                        radio.checked = false;
                    });
                });
            }
        });
    }

    // Auto-submit form when filter changes (for better UX)
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        const autoSubmitInputs = filterForm.querySelectorAll('input[type="radio"], input[type="checkbox"]');
        autoSubmitInputs.forEach(input => {
            input.addEventListener('change', function () {
                // Add a small delay to allow for better UX
                setTimeout(() => {
                    filterForm.submit();
                }, 300);
            });
        });
    }

    // Smooth scrolling for pagination
    const paginationLinks = document.querySelectorAll('.pagination a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.href;

            // Add loading state
            document.body.classList.add('loading');

            // Navigate to new page
            window.location.href = url;
        });
    });

    // Add animation classes to product grid items
    const productItems = document.querySelectorAll('.product-card');
    productItems.forEach((item, index) => {
        item.classList.add('product-grid-item');
    });

    // Lazy load images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Add to cart functionality
    window.addToCart = function (productId) {
        const button = event.target.closest('button');
        const originalText = button.innerHTML;

        // Show loading state
        button.innerHTML = '<svg class="animate-spin h-4 w-4 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        button.disabled = true;

        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count if exists
                    const cartCount = document.querySelector('.cart-counter');
                    if (cartCount && data.cartCount) {
                        cartCount.textContent = data.cartCount;
                        cartCount.classList.remove('opacity-0');

                        // Add bounce animation to cart icon
                        cartCount.classList.add('animate-bounce');
                        setTimeout(() => {
                            cartCount.classList.remove('animate-bounce');
                        }, 1000);
                    }

                    // Show success feedback
                    button.innerHTML = '<svg class="w-4 h-4 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';

                    // Reset button after delay
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, 2000);

                } else {
                    // Show error state
                    button.innerHTML = '❌';
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, 2000);

                    alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                button.innerHTML = originalText;
                button.disabled = false;
                alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!');
            });
    };

    // Wishlist functionality (placeholder)
    window.addToWishlist = function (productId) {
        // Add to wishlist logic here
        alert('Tính năng wishlist sẽ được cập nhật sớm!');
    };

    // Quick view functionality
    window.quickView = function (productId) {
        window.location.href = `/products/${productId}`;
    };
});