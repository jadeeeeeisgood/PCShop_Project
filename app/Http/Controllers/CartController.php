<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CartController extends Controller
{
    protected $cartService;
    protected $stockService;

    public function __construct(CartService $cartService, StockService $stockService)
    {
        $this->cartService = $cartService;
        $this->stockService = $stockService;
    }

    public function index()
    {
        try {
            $cartItems = $this->cartService->getCartItems();
            $total = $this->cartService->getCartTotal();

            return view('cart.index', compact('cartItems', 'total'));
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi tải giỏ hàng.');
        }
    }

    public function add(Request $request)
    {
        try {
            // Custom validation with JSON response for AJAX
            if ($request->expectsJson()) {
                $validator = \Validator::make($request->all(), [
                    'product_id' => 'required|exists:products,id',
                    'quantity' => 'required|integer|min:1',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
            } else {
                $request->validate([
                    'product_id' => 'required|exists:products,id',
                    'quantity' => 'required|integer|min:1',
                ]);
            }

            $result = $this->cartService->addProduct(
                $request->product_id,
                $request->quantity
            );

            $message = 'Đã thêm sản phẩm vào giỏ hàng.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'cart_count' => $this->cartService->getCartCount()
                ]);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Cart add error:', ['error' => $e->getMessage()]);

            $message = 'Có lỗi xảy ra: ' . $e->getMessage();

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return back()->with('error', $message);
        }
    }

    public function addProduct(Product $product, Request $request)
    {
        try {
            // Default quantity is 1 if not provided
            $quantity = $request->input('quantity', 1);

            // Validate quantity
            if ($quantity < 1) {
                $quantity = 1;
            }

            $result = $this->cartService->addProduct(
                $product->id,
                $quantity
            );

            $message = 'Đã thêm sản phẩm vào giỏ hàng.';

            // Always return JSON for API requests
            return response()->json([
                'success' => true,
                'message' => $message,
                'cartCount' => $this->cartService->getCartCount()
            ]);
        } catch (\Exception $e) {
            $message = 'Có lỗi xảy ra: ' . $e->getMessage();

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return back()->with('error', $message);
        }
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:' . $product->stock,
        ]);

        if (auth()->check()) {
            $cartItem = CartItem::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                if ($request->quantity > 0) {
                    $cartItem->update(['quantity' => $request->quantity]);
                } else {
                    $cartItem->delete();
                }
            }
        } else {
            $cart = session()->get('cart', []);
            if ($request->quantity > 0) {
                $cart[$product->id] = $request->quantity;
            } else {
                unset($cart[$product->id]);
            }
            session()->put('cart', $cart);
        }

        if ($request->expectsJson()) {
            $cartItems = $this->getCartItems();
            $total = $this->calculateTotal($cartItems);
            $cartCount = $this->getCartCount();

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật giỏ hàng.',
                'cart_count' => $cartCount,
                'cart_totals' => [
                    'subtotal' => number_format($total),
                    'tax' => number_format($total * 0.1),
                    'total' => number_format($total * 1.1 + 30000)
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function updateItem(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock,
        ]);

        if ($cartItem->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        $cartItems = $this->getCartItems();
        $total = $this->calculateTotal($cartItems);
        $cartCount = $this->getCartCount();

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật giỏ hàng.',
            'cart_count' => $cartCount,
            'cart_totals' => [
                'subtotal' => number_format($total),
                'tax' => number_format($total * 0.1),
                'total' => number_format($total * 1.1 + 30000)
            ]
        ]);
    }

    public function remove(Product $product)
    {
        try {
            if (auth()->check()) {
                CartItem::where('user_id', auth()->id())
                    ->where('product_id', $product->id)
                    ->delete();
            } else {
                $cart = session()->get('cart', []);
                unset($cart[$product->id]);
                session()->put('cart', $cart);
            }

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
                    'cart_count' => $this->getCartCount()
                ]);
            }

            return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Có lỗi xảy ra khi xóa sản phẩm.');
        }
    }

    public function removeItem(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.'
        ]);
    }

    public function clear()
    {
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            session()->forget('cart');
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng.');
    }

    public function count()
    {
        $count = 0;

        if (auth()->check()) {
            $count = CartItem::where('user_id', auth()->id())->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            $count = array_sum($cart);
        }

        return response()->json(['count' => $count]);
    }

    protected function getCartCount()
    {
        if (auth()->check()) {
            return CartItem::where('user_id', auth()->id())->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            return array_sum($cart);
        }
    }

    protected function getCartItems()
    {
        if (auth()->check()) {
            return CartItem::where('user_id', auth()->id())
                ->with('product.category')
                ->get();
        } else {
            $cart = session()->get('cart', []);
            $cartItems = collect();

            foreach ($cart as $productId => $quantity) {
                $product = Product::with('category')->find($productId);
                if ($product) {
                    $cartItems->push((object) [
                        'id' => $productId,
                        'product' => $product,
                        'quantity' => $quantity,
                    ]);
                }
            }

            return $cartItems;
        }
    }

    protected function calculateTotal($cartItems)
    {
        return $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    public function mergeGuestCart()
    {
        if (auth()->check() && session()->has('cart')) {
            $guestCart = session()->get('cart', []);

            foreach ($guestCart as $productId => $quantity) {
                $existingCartItem = CartItem::where('user_id', auth()->id())
                    ->where('product_id', $productId)
                    ->first();

                if ($existingCartItem) {
                    $existingCartItem->increment('quantity', $quantity);
                } else {
                    CartItem::create([
                        'user_id' => auth()->id(),
                        'product_id' => $productId,
                        'quantity' => $quantity,
                    ]);
                }
            }

            session()->forget('cart');
        }
    }

    public function items()
    {
        if (auth()->check()) {
            $cartItems = auth()->user()->cartItems()->with('product')->get();
        } else {
            $cart = session()->get('cart', []);
            $cartItems = collect();

            foreach ($cart as $productId => $quantity) {
                $product = Product::find($productId);
                if ($product) {
                    $cartItems->push((object) [
                        'product' => $product,
                        'quantity' => $quantity
                    ]);
                }
            }
        }

        $html = '';
        if ($cartItems->count() > 0) {
            foreach ($cartItems as $item) {
                $product = $item->product ?? $item->product;
                $quantity = $item->quantity ?? $item->quantity;
                $total = $product->price * $quantity;

                $html .= '<div class="cart-item">';
                if ($product->image) {
                    $html .= '<img src="' . Storage::url($product->image) . '" alt="' . $product->name . '">';
                } else {
                    $html .= '<img src="https://via.placeholder.com/50x50?text=No+Image" alt="' . $product->name . '">';
                }
                $html .= '<div class="flex-1">';
                $html .= '<div class="fw-bold">' . Str::limit($product->name, 30) . '</div>';
                $html .= '<div class="text-muted small">x' . $quantity . '</div>';
                $html .= '<div class="text-danger">' . number_format($total, 0, ',', '.') . 'đ</div>';
                $html .= '</div>';
                $html .= '<button class="btn btn-sm btn-outline-danger ms-2" onclick="removeFromCart(' . $product->id . ')">';
                $html .= '<i class="fa fa-times"></i>';
                $html .= '</button>';
                $html .= '</div>';
            }
        } else {
            $html = '<div class="text-center py-3"><p class="text-muted">Giỏ hàng trống</p></div>';
        }

        return response($html);
    }

    /**
     * Update multiple cart items
     */
    public function updateMultiple(Request $request)
    {
        try {
            $quantities = $request->input('quantities', []);

            foreach ($quantities as $productId => $quantity) {
                if ($quantity > 0) {
                    $this->cartService->updateQuantity((int) $productId, (int) $quantity);
                } else {
                    $this->cartService->removeProduct((int) $productId);
                }
            }

            return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được cập nhật.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật giỏ hàng: ' . $e->getMessage());
        }
    }

    /**
     * Get available stock for a product
     */
    public function getAvailableStock(Product $product)
    {
        $availableStock = $this->stockService->getAvailableStock($product->id);

        return response()->json([
            'product_id' => $product->id,
            'available_stock' => $availableStock,
            'total_stock' => $product->stock
        ]);
    }

    /**
     * Reserve stock for a product
     */
    public function reserveStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $reserved = $this->stockService->reserveStock(
            $request->product_id,
            $request->quantity,
            session()->getId()
        );

        if ($reserved) {
            return response()->json([
                'success' => true,
                'message' => 'Stock reserved successfully',
                'available_stock' => $this->stockService->getAvailableStock($request->product_id)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không đủ hàng trong kho'
        ], 400);
    }

    /**
     * Release reserved stock
     */
    public function releaseStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $this->stockService->releaseStock(
            $request->product_id,
            session()->getId()
        );

        return response()->json([
            'success' => true,
            'message' => 'Stock released successfully',
            'available_stock' => $this->stockService->getAvailableStock($request->product_id)
        ]);
    }
}
