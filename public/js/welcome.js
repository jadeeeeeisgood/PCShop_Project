// Welcome Page JavaScript

document.addEventListener('DOMContentLoaded', function () {
    // Initialize carousel
    initializeCarousel();

    // Initialize scroll animations
    initializeScrollAnimations();

    // Initialize product interactions
    initializeProductInteractions();

    // Initialize lazy loading
    initializeLazyLoading();
});

/**
 * Initialize banner carousel
 */
function initializeCarousel() {
    const carousel = document.querySelector('#mainCarousel');
    if (carousel) {
        // Auto-advance carousel
        setInterval(() => {
            const nextButton = carousel.querySelector('[data-bs-slide="next"]');
            if (nextButton) {
                nextButton.click();
            }
        }, 8000); // Change slide every 8 seconds
    }
}

/**
 * Initialize scroll animations
 */
function initializeScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
            }
        });
    }, observerOptions);

    // Observe all elements with animate-on-scroll class
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
}

/**
 * Initialize product interactions
 */
function initializeProductInteractions() {
    // Add to cart buttons
    document.querySelectorAll('.btn-add-cart').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const productId = this.dataset.productId;
            const quantity = 1;

            if (productId) {
                addToCart(productId, quantity, this);
            }
        });
    });

    // Product card hover effects
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-8px)';
        });

        card.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0)';
        });
    });
}

/**
 * Add product to cart
 */
function addToCart(productId, quantity, buttonElement) {
    const originalText = buttonElement.textContent;
    const originalDisabled = buttonElement.disabled;

    // Update button state
    buttonElement.disabled = true;
    buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang thêm...';

    // Make AJAX request
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count
                updateCartCount(data.cart_count);

                // Show success notification
                showNotification('Đã thêm sản phẩm vào giỏ hàng!', 'success');

                // Update button state temporarily
                buttonElement.innerHTML = '<i class="fas fa-check me-2"></i>Đã thêm';
                buttonElement.classList.add('btn-success');
                buttonElement.classList.remove('btn-add-cart');

                // Reset button after 2 seconds
                setTimeout(() => {
                    buttonElement.innerHTML = originalText;
                    buttonElement.classList.remove('btn-success');
                    buttonElement.classList.add('btn-add-cart');
                    buttonElement.disabled = originalDisabled;
                }, 2000);
            } else {
                showNotification(data.message || 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!', 'error');
                buttonElement.innerHTML = originalText;
                buttonElement.disabled = originalDisabled;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!', 'error');
            buttonElement.innerHTML = originalText;
            buttonElement.disabled = originalDisabled;
        });
}

/**
 * Update cart count in navigation
 */
function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('.cart-count, .cart-items-count');
    cartCountElements.forEach(element => {
        element.textContent = count;
    });
}

/**
 * Show notification
 */
function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' :
        type === 'error' ? 'alert-danger' : 'alert-info';

    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <span>${message}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto-dismiss after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

/**
 * Initialize lazy loading for images
 */
function initializeLazyLoading() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
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
}

/**
 * Format currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

/**
 * Animate counter numbers
 */
function animateCounters() {
    document.querySelectorAll('.stat-number').forEach(counter => {
        const target = parseInt(counter.textContent);
        const increment = target / 100;
        let current = 0;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(current);
        }, 20);
    });
}

// Initialize counter animation when stats section comes into view
const statsSection = document.querySelector('.stats-section');
if (statsSection) {
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    statsObserver.observe(statsSection);
}