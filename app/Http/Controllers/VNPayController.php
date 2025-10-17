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
            // Validate order belongs to current user
            if (auth()->check() && $order->user_id !== auth()->id()) {
                return redirect()->route('cart.index')->with('error', 'Đơn hàng không tồn tại.');
            }

            // Check if order is already paid
            if ($order->status === 'paid' || $order->status === 'processing') {
                return redirect()->route('checkout.success', $order->id);
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
            $result = $this->vnPayService->verifyCallback($request->all());

            if ($result['success']) {
                return DB::transaction(function () use ($result, $request) {
                    $order = Order::find($result['order_id']);

                    if (!$order) {
                        return redirect()->route('welcome')
                            ->with('error', 'Đơn hàng không tồn tại.');
                    }

                    // Update order status
                    $order->update([
                        'status' => 'paid',
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
                        auth()->user()->cartItems()->delete();
                    } else {
                        session()->forget('cart');
                    }

                    return redirect()->route('checkout.success', $order->id)
                        ->with('success', 'Thanh toán thành công!');
                });

            } else {
                return redirect()->route('checkout.index')
                    ->with('error', 'Thanh toán thất bại: ' . $result['message']);
            }

        } catch (\Exception $e) {
            Log::error('VNPay callback error: ' . $e->getMessage());
            return redirect()->route('checkout.index')
                ->with('error', 'Có lỗi xảy ra trong quá trình xử lý thanh toán.');
        }
    }
}
