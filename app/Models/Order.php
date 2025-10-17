<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total',
        'subtotal',
        'shipping_fee',
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'payment_method',
        'payment_status',
        'notes'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    /**
     * Get the order's formatted total.
     */
    protected function formattedTotal(): Attribute
    {
        return Attribute::make(
            get: fn() => number_format((float) $this->total, 0, ',', '.') . ' VNĐ',
        );
    }

    /**
     * Get the order's formatted subtotal.
     */
    protected function formattedSubtotal(): Attribute
    {
        return Attribute::make(
            get: fn() => number_format((float) $this->subtotal, 0, ',', '.') . ' VNĐ',
        );
    }

    /**
     * Get the order's formatted shipping fee.
     */
    protected function formattedShippingFee(): Attribute
    {
        return Attribute::make(
            get: fn() => number_format((float) $this->shipping_fee, 0, ',', '.') . ' VNĐ',
        );
    }

    /**
     * Get the status color for UI.
     */
    protected function statusColor(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->status) {
                'pending' => 'yellow',
                'processing' => 'blue',
                'shipped' => 'indigo',
                'delivered' => 'green',
                'canceled' => 'red',
                default => 'gray'
            }
        );
    }

    /**
     * Get the status label in Vietnamese.
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->status) {
                'pending' => 'Chờ xử lý',
                'processing' => 'Đang xử lý',
                'shipped' => 'Đã giao hàng',
                'delivered' => 'Đã giao thành công',
                'canceled' => 'Đã hủy',
                default => 'Không xác định'
            }
        );
    }

    /**
     * Get the payment method label in Vietnamese.
     */
    protected function paymentMethodLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->payment_method) {
                'cod' => 'Thanh toán khi nhận hàng',
                'paypal' => 'PayPal',
                'stripe' => 'Stripe (Thẻ tín dụng)',
                'vnpay' => 'VNPay',
                default => 'Không xác định'
            }
        );
    }

    /**
     * Scope for filtering by status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by payment method.
     */
    public function scopePaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope for recent orders.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
