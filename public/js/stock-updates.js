/**
 * Real-time Stock Updates with Pusher
 */

class StockManager {
    constructor() {
        this.pusher = null;
        this.channel = null;
        this.initializePusher();
        this.setupEventListeners();
    }

    initializePusher() {
        try {
            // Initialize Pusher
            this.pusher = new Pusher(window.pusherConfig.key, {
                cluster: window.pusherConfig.cluster,
                encrypted: true
            });

            // Subscribe to stock updates channel
            this.channel = this.pusher.subscribe('stock-updates');

            // Listen for stock update events
            this.channel.bind('stock-updated', (data) => {
                this.handleStockUpdate(data);
            });

            console.log('Pusher initialized successfully');
        } catch (error) {
            console.error('Failed to initialize Pusher:', error);
        }
    }

    setupEventListeners() {
        // Update stock displays when page loads
        document.addEventListener('DOMContentLoaded', () => {
            this.updateAllStockDisplays();
        });

        // Handle quantity changes in cart
        document.addEventListener('change', (e) => {
            if (e.target.matches('[data-quantity-input]')) {
                this.handleQuantityChange(e.target);
            }
        });

        // Handle add to cart buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-add-to-cart]')) {
                this.handleAddToCart(e.target);
            }
        });
    }

    handleStockUpdate(data) {
        const { product_id, available_stock } = data;

        // Update all stock displays for this product
        this.updateStockDisplay(product_id, available_stock);

        // Update quantity input max values
        this.updateQuantityInputs(product_id, available_stock);

        // Show notification if stock is low
        if (available_stock <= 5) {
            this.showLowStockNotification(product_id, available_stock);
        }

        // Disable add to cart if out of stock
        if (available_stock === 0) {
            this.disableAddToCart(product_id);
        }
    }

    updateStockDisplay(productId, availableStock) {
        const stockElements = document.querySelectorAll(`[data-stock-display="${productId}"]`);

        stockElements.forEach(element => {
            if (availableStock > 0) {
                element.textContent = `Còn lại: ${availableStock}`;
                element.className = availableStock <= 5 ? 'text-warning small' : 'text-success small';
            } else {
                element.textContent = 'Hết hàng';
                element.className = 'text-danger small';
            }
        });
    }

    updateQuantityInputs(productId, availableStock) {
        const quantityInputs = document.querySelectorAll(`[data-product-id="${productId}"] input[type="number"]`);

        quantityInputs.forEach(input => {
            input.max = availableStock;
            if (parseInt(input.value) > availableStock) {
                input.value = availableStock;
            }
        });
    }

    showLowStockNotification(productId, availableStock) {
        // Create and show toast notification
        const toast = document.createElement('div');
        toast.className = 'toast show position-fixed top-0 end-0 m-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="toast-header bg-warning">
                <strong class="me-auto">Cảnh báo tồn kho</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Sản phẩm chỉ còn lại ${availableStock} sản phẩm!
            </div>
        `;

        document.body.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 5000);
    }

    disableAddToCart(productId) {
        const addToCartButtons = document.querySelectorAll(`[data-product-id="${productId}"] [data-add-to-cart]`);

        addToCartButtons.forEach(button => {
            button.disabled = true;
            button.textContent = 'Hết hàng';
            button.className = button.className.replace('btn-primary', 'btn-secondary');
        });
    }

    async updateAllStockDisplays() {
        const stockElements = document.querySelectorAll('[data-stock-display]');
        const productIds = [...new Set(Array.from(stockElements).map(el => el.dataset.stockDisplay))];

        for (const productId of productIds) {
            try {
                const response = await fetch(`/api/stock/${productId}`);
                const data = await response.json();
                this.updateStockDisplay(productId, data.available_stock);
            } catch (error) {
                console.error(`Failed to fetch stock for product ${productId}:`, error);
            }
        }
    }

    async handleQuantityChange(input) {
        const productId = input.closest('[data-product-id]')?.dataset.productId;
        const quantity = parseInt(input.value);

        if (!productId || quantity < 1) return;

        try {
            // Check available stock
            const response = await fetch(`/api/stock/${productId}`);
            const data = await response.json();

            if (quantity > data.available_stock) {
                input.value = data.available_stock;
                this.showToast('Số lượng vượt quá tồn kho có sẵn', 'warning');
            }
        } catch (error) {
            console.error('Failed to check stock:', error);
        }
    }

    async handleAddToCart(button) {
        const productId = button.dataset.productId || button.closest('[data-product-id]')?.dataset.productId;
        const quantityInput = button.closest('.product-item')?.querySelector('input[type="number"]');
        const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

        if (!productId) return;

        try {
            // Reserve stock before adding to cart
            const reserveResponse = await fetch('/api/stock/reserve', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            });

            const reserveData = await reserveResponse.json();

            if (!reserveData.success) {
                this.showToast(reserveData.message, 'error');
                return;
            }

            // Update stock display
            this.updateStockDisplay(productId, reserveData.available_stock);

        } catch (error) {
            console.error('Failed to reserve stock:', error);
            this.showToast('Có lỗi xảy ra khi kiểm tra tồn kho', 'error');
        }
    }

    showToast(message, type = 'info') {
        const toastContainer = document.getElementById('toast-container') || this.createToastContainer();

        const toast = document.createElement('div');
        toast.className = `toast show mb-2`;
        toast.innerHTML = `
            <div class="toast-header bg-${type === 'error' ? 'danger' : type === 'warning' ? 'warning' : 'primary'} text-white">
                <strong class="me-auto">Thông báo</strong>
                <button type="button" class="btn-close btn-close-white" onclick="this.closest('.toast').remove()"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;

        toastContainer.appendChild(toast);

        // Auto remove after 4 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 4000);
    }

    createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }
}

// Initialize stock manager when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.stockManager = new StockManager();
    });
} else {
    window.stockManager = new StockManager();
}