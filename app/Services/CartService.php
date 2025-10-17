<?php

namespace App\Services;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;

class CartService
{
    /**
     * Add product to cart
     */
    public function addProduct(int $productId, int $quantity = 1): array
    {
        $product = Product::findOrFail($productId);

        // Check if product is in stock
        if ($product->stock < $quantity) {
            throw new \Exception('Sản phẩm không đủ số lượng trong kho');
        }

        if (Auth::check()) {
            return $this->addToUserCart($product, $quantity);
        } else {
            return $this->addToSessionCart($product, $quantity);
        }
    }

    /**
     * Remove product from cart
     */
    public function removeProduct(int $productId): array
    {
        if (Auth::check()) {
            return $this->removeFromUserCart($productId);
        } else {
            return $this->removeFromSessionCart($productId);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity(int $productId, int $quantity): array
    {
        if ($quantity <= 0) {
            return $this->removeProduct($productId);
        }

        $product = Product::findOrFail($productId);

        if ($product->stock < $quantity) {
            throw new \Exception('Sản phẩm không đủ số lượng trong kho');
        }

        if (Auth::check()) {
            return $this->updateUserCartQuantity($productId, $quantity);
        } else {
            return $this->updateSessionCartQuantity($productId, $quantity);
        }
    }

    /**
     * Get cart items
     */
    public function getCartItems(): Collection
    {
        if (Auth::check()) {
            return collect($this->getUserCartItems());
        } else {
            return collect($this->getSessionCartItems());
        }
    }

    /**
     * Get cart total
     */
    public function getCartTotal(): array
    {
        $items = $this->getCartItems();
        $subtotal = 0;
        $totalQuantity = 0;

        foreach ($items as $item) {
            $subtotal += $item->product->price * $item->quantity;
            $totalQuantity += $item->quantity;
        }

        return [
            'subtotal' => $subtotal,
            'tax' => $subtotal * 0.1, // 10% tax
            'total' => $subtotal * 1.1,
            'quantity' => $totalQuantity
        ];
    }

    /**
     * Clear cart
     */
    public function clearCart(): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            Session::forget('cart');
        }
    }

    /**
     * Merge session cart to user cart when user logs in
     */
    public function mergeSessionCartToUser(): void
    {
        $sessionCart = Session::get('cart', []);

        if (!empty($sessionCart) && Auth::check()) {
            foreach ($sessionCart as $productId => $item) {
                $this->addToUserCart(
                    Product::find($productId),
                    $item['quantity']
                );
            }

            Session::forget('cart');
        }
    }

    // Private methods

    private function addToUserCart(Product $product, int $quantity): array
    {
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }

        return [
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng',
            'cart_count' => $this->getUserCartCount()
        ];
    }

    private function addToSessionCart(Product $product, int $quantity): array
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity
            ];
        }

        Session::put('cart', $cart);

        return [
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng',
            'cart_count' => $this->getSessionCartCount()
        ];
    }

    private function removeFromUserCart(int $productId): array
    {
        CartItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        return [
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
            'cart_count' => $this->getUserCartCount()
        ];
    }

    private function removeFromSessionCart(int $productId): array
    {
        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::put('cart', $cart);

        return [
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
            'cart_count' => $this->getSessionCartCount()
        ];
    }

    private function updateUserCartQuantity(int $productId, int $quantity): array
    {
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
        }

        return [
            'success' => true,
            'message' => 'Đã cập nhật số lượng sản phẩm',
            'cart_count' => $this->getUserCartCount()
        ];
    }

    private function updateSessionCartQuantity(int $productId, int $quantity): array
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }

        return [
            'success' => true,
            'message' => 'Đã cập nhật số lượng sản phẩm',
            'cart_count' => $this->getSessionCartCount()
        ];
    }

    private function getUserCartItems(): array
    {
        return CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($item) {
                return (object) [
                    'id' => $item->product->id,
                    'product' => $item->product,
                    'quantity' => $item->quantity
                ];
            })
            ->toArray();
    }

    private function getSessionCartItems(): array
    {
        $cart = Session::get('cart', []);
        $items = [];

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $items[] = (object) [
                    'id' => $productId,
                    'product' => $product,
                    'quantity' => $item['quantity']
                ];
            }
        }

        return $items;
    }

    private function getUserCartCount(): int
    {
        return CartItem::where('user_id', Auth::id())->sum('quantity');
    }

    private function getSessionCartCount(): int
    {
        $cart = Session::get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Get cart count
     */
    public function getCartCount(): int
    {
        if (auth()->check()) {
            return CartItem::where('user_id', auth()->id())->sum('quantity');
        }

        $cart = session()->get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }
}