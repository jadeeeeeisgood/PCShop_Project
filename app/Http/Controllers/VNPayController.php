<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\VNPayService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class VNPayController extends Controller
{
    protected $vnPayService;
    protected $stockService;

    public function __construct(VNPayService $vnPayService, StockService $stockService)
    {
        $this->vnPayService = $vnPayService;
        $this->stockService = $stockService;
    }

    /**
     * Create VNPay payment
     */
    public function createPayment(Request $request, Order $order)
    {
        try {
            Log::info('VNPay createPayment started', [
                'order_id' => $order->id,
                'order_user_id' => $order->user_id,
                'current_user_id' => auth()->id(),
                'is_authenticated' => auth()->check()
            ]);

            // Validate order belongs to current user
            if (auth()->check() && $order->user_id !== auth()->id()) {
                Log::warning('VNPay: Order does not belong to current user', [
                    'order_id' => $order->id,
                    'order_user_id' => $order->user_id,
                    'current_user_id' => auth()->id()
                ]);
                return redirect()->route('cart.index')->with('error', 'Đơn hàng không tồn tại.');
            }

            // Check if order is already paid
            if ($order->status === 'paid' || $order->status === 'processing') {
                Log::info('VNPay: Order already paid', ['order_id' => $order->id, 'status' => $order->status]);
                return redirect()->route('checkout.success', $order->id);
            }

            // Check if order is canceled
            if ($order->status === 'canceled') {
                Log::info('VNPay: Order is canceled', ['order_id' => $order->id, 'status' => $order->status]);
                return redirect()->route('profile.orders')->with('error', 'Không thể thanh toán đơn hàng đã hủy.');
            }

            // Check if order is not pending
            if ($order->status !== 'pending') {
                Log::info('VNPay: Order status not allowed for payment', ['order_id' => $order->id, 'status' => $order->status]);
                return redirect()->route('profile.orders')->with('error', 'Không thể thanh toán đơn hàng này.');
            }

            // Reserve stock for this order
            foreach ($order->orderItems as $item) {
                $reserved = $this->stockService->reserveStock(
                    $item->product_id,
                    $item->quantity,
                    session()->getId()
                );

                if (!$reserved) {
                    return redirect()->route('cart.index')
                        ->with('error', 'Sản phẩm "' . $item->product->name . '" không đủ số lượng trong kho.');
                }
            }

            // Create VNPay payment URL
            $paymentUrl = $this->vnPayService->createPaymentUrl($order, $request->ip());

            Log::info('VNPay redirect URL created', [
                'order_id' => $order->id,
                'payment_url' => $paymentUrl,
                'return_url' => config('services.vnpay.return_url')
            ]);

            return redirect($paymentUrl);

        } catch (\Exception $e) {
            Log::error('VNPay create payment error: ' . $e->getMessage());
            return redirect()->route('checkout.index')
                ->with('error', 'Có lỗi xảy ra khi tạo thanh toán. Vui lòng thử lại.');
        }
    }

    /**
     * Handle VNPay callback
     */
    public function callback(Request $request)
    {
        try {
            Log::info('VNPay callback started', ['request_data' => $request->all()]);

            $result = $this->vnPayService->verifyCallback($request->all());
            Log::info('VNPay verification result', $result);

            if ($result['success']) {
                return DB::transaction(function () use ($result, $request) {
                    $order = Order::find($result['order_id']);

                    if (!$order) {
                        Log::error('VNPay callback: Order not found', ['order_id' => $result['order_id']]);
                        return redirect()->route('welcome')
                            ->with('error', 'Đơn hàng không tồn tại.');
                    }

                    Log::info('VNPay callback: Updating order', [
                        'order_id' => $order->id,
                        'current_payment_status' => $order->payment_status,
                        'new_payment_status' => 'completed'
                    ]);

                    // Update order payment status
                    $order->update([
                        'payment_status' => 'completed',
                        'payment_method' => 'vnpay'
                    ]);

                    // Create payment transaction record
                    PaymentTransaction::create([
                        'order_id' => $order->id,
                        'transaction_id' => $result['transaction_id'],
                        'amount' => $result['amount'],
                        'status' => 'completed',
                        'payment_method' => 'vnpay',
                        'gateway_response' => json_encode($request->all())
                    ]);

                    // Confirm stock reduction
                    foreach ($order->orderItems as $item) {
                        $this->stockService->confirmStockReduction(
                            $item->product_id,
                            $item->quantity,
                            session()->getId()
                        );
                    }

                    // Clear cart
                    if (auth()->check()) {
                        $cartCount = auth()->user()->cartItems()->count();
                        auth()->user()->cartItems()->delete();
                        Log::info('VNPay callback: Cleared authenticated user cart', ['items_cleared' => $cartCount]);
                    } else {
                        session()->forget('cart');
                        Log::info('VNPay callback: Cleared guest cart from session');
                    }

                    Log::info('VNPay callback: Payment processed successfully', ['order_id' => $order->id]);

                    // Store order ID in session for success page
                    session(['completed_order_id' => $order->id]);

                    // Ensure user is still authenticated
                    if ($order->user_id && !auth()->check()) {
                        Log::warning('VNPay callback: User session lost, attempting to re-authenticate', [
                            'order_user_id' => $order->user_id,
                            'current_auth' => auth()->check()
                        ]);
                        // Re-login the user
                        $user = \App\Models\User::find($order->user_id);
                        if ($user) {
                            Auth::login($user);
                            Log::info('VNPay callback: User re-authenticated successfully');
                        }
                    }

                    return redirect()->route('checkout.success', $order->id)
                        ->with('success', 'Thanh toán thành công!');
                });

            } else {
                Log::warning('VNPay callback: Payment verification failed', $result);
                return redirect()->route('checkout.index')
                    ->with('error', 'Thanh toán thất bại: ' . $result['message']);
            }

        } catch (\Exception $e) {
            Log::error('VNPay callback error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            return redirect()->route('checkout.index')
                ->with('error', 'Có lỗi xảy ra trong quá trình xử lý thanh toán.');
        }
    }
}
