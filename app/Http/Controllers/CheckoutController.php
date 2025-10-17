<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Check stock availability
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Sản phẩm '{$item->product->name}' không đủ số lượng trong kho.");
            }
        }

        $total = $this->calculateTotal($cartItems);
        $shippingFee = $this->calculateShipping($total);
        $finalTotal = $total + $shippingFee;

        return view('checkout.index', compact('cartItems', 'total', 'shippingFee', 'finalTotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'payment_method' => 'required|in:paypal,stripe,vnpay,cod',
            'notes' => 'nullable|string|max:500',
        ]);

        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Final stock check
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Sản phẩm '{$item->product->name}' không đủ số lượng trong kho.");
            }
        }

        $total = $this->calculateTotal($cartItems);
        $shippingFee = $this->calculateShipping($total);
        $finalTotal = $total + $shippingFee;

        $order = null;

        DB::transaction(function () use ($request, $cartItems, $total, $shippingFee, $finalTotal, &$order) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $finalTotal,
                'subtotal' => $total,
                'shipping_fee' => $shippingFee,
                'status' => 'pending',
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'pending',
                'notes' => $request->notes,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                // Decrease stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Clear cart
            $this->clearCart();

            // Send confirmation email
            try {
                Mail::to($request->customer_email)->send(new OrderConfirmation($order));
            } catch (\Exception $e) {
                // Log error but don't fail the transaction
                \Log::error('Email sending failed: ' . $e->getMessage());
            }
        });

        // Handle payment processing
        if ($request->payment_method === 'vnpay') {
            return redirect()->route('payment.vnpay', ['order' => $order->id]);
        } elseif ($request->payment_method !== 'cod') {
            // Handle other payment methods if needed
            return redirect()->route('checkout.success', ['order' => $order->id])
                ->with('info', 'Đơn hàng đã được tạo. Vui lòng hoàn tất thanh toán.');
        }

        return redirect()->route('checkout.success', ['order' => $order->id])
            ->with('success', 'Đơn hàng đã được đặt thành công!');
    }

    public function success(Order $order = null)
    {
        if (!$order && session()->has('order_id')) {
            $order = Order::find(session('order_id'));
            session()->forget('order_id');
        }

        return view('checkout.success', compact('order'));
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

    protected function calculateShipping($total)
    {
        // Free shipping for orders over 2,000,000 VND
        if ($total >= 2000000) {
            return 0;
        }

        // Standard shipping fee
        return 50000; // 50,000 VND
    }

    protected function clearCart()
    {
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            session()->forget('cart');
        }
    }

    public function orderTracking(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'email' => 'required|email',
        ]);

        $order = Order::where('id', $request->order_id)
            ->where('customer_email', $request->email)
            ->with('orderItems.product')
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng với thông tin đã cung cấp.');
        }

        return view('checkout.tracking', compact('order'));
    }
}
