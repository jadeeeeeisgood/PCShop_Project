/**
 * Layout JavaScript
 * Handles global functionality for the shop layout
 */

class ShopLayout {
    constructor() {
        this.init();
    }

    init() {
        this.setupCartDropdown();
        this.setupNavigation();
        this.setupSearch();
        this.setupGlobalEventListeners();
        this.initMobileNavigation();
    }

    // Mobile Navigation Management
    initMobileNavigation() {
        // Handle mobile navigation behavior
        if (window.innerWidth <= 991.98) {
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                const link = item.querySelector('a');
                const dropdown = item.querySelector('.dropdown-menu-custom');

                if (dropdown && link) {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        const isActive = dropdown.style.maxHeight && dropdown.style.maxHeight !== '0px';

                        // Close all other dropdowns
                        navItems.forEach(otherItem => {
                            const otherDropdown = otherItem.querySelector('.dropdown-menu-custom');
                            if (otherDropdown && otherDropdown !== dropdown) {
                                otherDropdown.style.maxHeight = '0px';
                            }
                        });

                        // Toggle current dropdown
                        dropdown.style.maxHeight = isActive ? '0px' : '300px';
                    });
                }
            });
        }
    }

    // Cart Dropdown Management
    setupCartDropdown() {
        // Toggle cart dropdown
        $(document).on('click', '.top-cart-info', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.toggleCart();
        });

        // Close cart when clicking outside
        $(document).on('click', (e) => {
            if (!$(e.target).closest('.top-cart-block').length) {
                this.closeCart();
            }
        });
    }

    toggleCart() {
        const dropdown = $('#cart-dropdown');
        if (dropdown.is(':visible')) {
            this.closeCart();
        } else {
            this.openCart();
        }
    }

    openCart() {
        $('#cart-dropdown').show();
        this.loadCartItems();
    }

    closeCart() {
        $('#cart-dropdown').hide();
    }

    loadCartItems() {
        const cartContainer = $('#cart-items');

        cartContainer.html('<div class="text-center p-3"><div class="spinner"></div> Đang tải...</div>');

        $.get('/cart/items')
            .done((data) => {
                cartContainer.html(data);
            })
            .fail(() => {
                cartContainer.html('<div class="text-center p-3 text-danger">Không thể tải giỏ hàng</div>');
            });
    }

    // Cart Actions
    addToCart(productId, quantity = 1) {
        const button = $(`button[onclick*="${productId}"]`);
        const originalText = button.html();

        // Show loading state
        button.prop('disabled', true)
            .addClass('loading')
            .html('<span class="spinner"></span> Đang thêm...');

        return $.post('/cart/add', {
            product_id: productId,
            quantity: quantity,
            _token: $('meta[name="csrf-token"]').attr('content')
        })
            .done((response) => {
                if (response.success) {
                    this.updateCartCount(response.cart_count);
                    this.showNotification('Đã thêm sản phẩm vào giỏ hàng!', 'success');

                    // Update button to success state
                    button.removeClass('loading')
                        .html('<i class="fa fa-check"></i> Đã thêm');

                    setTimeout(() => {
                        button.html(originalText);
                    }, 2000);
                } else {
                    throw new Error(response.message || 'Có lỗi xảy ra');
                }
            })
            .fail((xhr) => {
                const errorMessage = xhr.responseJSON?.message || 'Không thể thêm sản phẩm vào giỏ hàng';
                this.showNotification(errorMessage, 'error');

                button.removeClass('loading').html(originalText);
            })
            .always(() => {
                button.prop('disabled', false);
            });
    }

    removeFromCart(productId) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            return;
        }

        return $.ajax({
            url: `/cart/remove/${productId}`,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        })
            .done((response) => {
                if (response.success) {
                    this.updateCartCount(response.cart_count);
                    this.loadCartItems();
                    this.showNotification('Đã xóa sản phẩm khỏi giỏ hàng!', 'success');
                }
            })
            .fail(() => {
                this.showNotification('Có lỗi xảy ra. Vui lòng thử lại.', 'error');
            });
    }

    updateCartCount(count) {
        $('#cart-count').text(count);

        // Add animation
        $('#cart-count').parent().addClass('animate__animated animate__pulse');
        setTimeout(() => {
            $('#cart-count').parent().removeClass('animate__animated animate__pulse');
        }, 1000);
    }

    // Navigation
    setupNavigation() {
        // Add active class to current page
        const currentUrl = window.location.pathname;
        $('.navbar-nav .nav-link').each(function () {
            if ($(this).attr('href') === currentUrl) {
                $(this).parent().addClass('active');
            }
        });

        // Mobile menu enhancement
        $('.navbar-toggler').on('click', function () {
            $(this).toggleClass('active');
        });
    }

    // Search Enhancement
    setupSearch() {
        const searchForm = $('.search-form');
        const searchInput = searchForm.find('input[name="search"]');

        // Add search suggestions (if needed in future)
        searchInput.on('input', debounce(() => {
            // Implement search suggestions here
        }, 300));

        // Submit on enter
        searchInput.on('keypress', (e) => {
            if (e.which === 13) {
                searchForm.submit();
            }
        });
    }

    // Global Event Listeners
    setupGlobalEventListeners() {
        // Handle all add to cart buttons
        $(document).on('click', '[data-add-to-cart]', (e) => {
            e.preventDefault();
            const productId = $(e.currentTarget).data('add-to-cart');
            const quantity = $(e.currentTarget).data('quantity') || 1;
            this.addToCart(productId, quantity);
        });

        // Handle remove from cart buttons
        $(document).on('click', '[data-remove-from-cart]', (e) => {
            e.preventDefault();
            const productId = $(e.currentTarget).data('remove-from-cart');
            this.removeFromCart(productId);
        });

        // Smooth scrolling for anchor links
        $(document).on('click', 'a[href^="#"]', function (e) {
            const target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 1000);
            }
        });

        // Handle window resize for mobile navigation
        $(window).on('resize', () => {
            if (window.innerWidth > 991.98) {
                // Reset mobile dropdown states on desktop
                document.querySelectorAll('.dropdown-menu-custom').forEach(dropdown => {
                    dropdown.style.maxHeight = '';
                });
            }
        });
    }

    // Notification System
    showNotification(message, type = 'info') {
        // Remove existing notifications
        $('.notification').remove();

        const notification = $(`
            <div class="notification notification-${type} animate__animated animate__fadeInRight">
                <div class="notification-content">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close">&times;</button>
                </div>
            </div>
        `);

        $('body').append(notification);

        // Auto remove after 4 seconds
        setTimeout(() => {
            notification.addClass('animate__fadeOutRight');
            setTimeout(() => notification.remove(), 500);
        }, 4000);

        // Manual close
        notification.find('.notification-close').on('click', () => {
            notification.addClass('animate__fadeOutRight');
            setTimeout(() => notification.remove(), 500);
        });
    }
}

// Utility Functions
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction() {
        const context = this;
        const args = arguments;

        const later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };

        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);

        if (callNow) func.apply(context, args);
    };
}

// Global functions for backward compatibility
function toggleCart() {
    window.shopLayout.toggleCart();
}

function addToCart(productId, quantity = 1) {
    return window.shopLayout.addToCart(productId, quantity);
}

function removeFromCart(productId) {
    return window.shopLayout.removeFromCart(productId);
}

// Initialize when document is ready
$(document).ready(function () {
    // Initialize layout
    window.shopLayout = new ShopLayout();

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Initialize popovers
    $('[data-bs-toggle="popover"]').popover();

    // Add loading states to all forms
    $('form').on('submit', function () {
        $(this).find('button[type="submit"]').prop('disabled', true).addClass('loading');
    });
});