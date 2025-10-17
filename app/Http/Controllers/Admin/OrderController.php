<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdate;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Order::with('user', 'orderItems.product');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by customer info
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        // Statistics
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'total_revenue' => Order::where('status', '!=', 'canceled')->sum('total'),
            'today_orders' => Order::whereDate('created_at', today())->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'orderItems.product', 'paymentTransactions');
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,canceled',
            'payment_status' => 'nullable|in:pending,paid,failed',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $order->status;

        $updateData = [
            'status' => $request->status,
        ];

        if ($request->has('payment_status')) {
            $updateData['payment_status'] = $request->payment_status;
        }

        if ($request->has('notes')) {
            $updateData['notes'] = $request->notes;
        }

        $order->update($updateData);

        // Send email notification if status changed
        if ($oldStatus !== $request->status) {
            try {
                Mail::to($order->customer_email)->send(new OrderStatusUpdate($order));
            } catch (\Exception $e) {
                \Log::error('Order status email failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.orders.index')
            ->with('success', 'Đơn hàng đã được cập nhật thành công.');
    }

    public function exportInvoice(Order $order)
    {
        $order->load('orderItems.product');

        // For now, return a simple HTML view instead of PDF
        return view('admin.orders.invoice', compact('order'));
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'action' => 'required|in:mark_processing,mark_shipped,mark_delivered,mark_canceled',
        ]);

        $status = match ($request->action) {
            'mark_processing' => 'processing',
            'mark_shipped' => 'shipped',
            'mark_delivered' => 'delivered',
            'mark_canceled' => 'canceled',
        };

        $orders = Order::whereIn('id', $request->order_ids)->get();

        foreach ($orders as $order) {
            if ($order->status !== $status) {
                $order->update(['status' => $status]);

                try {
                    Mail::to($order->customer_email)->send(new OrderStatusUpdate($order));
                } catch (\Exception $e) {
                    \Log::error('Bulk order status email failed: ' . $e->getMessage());
                }
            }
        }

        return redirect()->back()
            ->with('success', "Đã cập nhật {$orders->count()} đơn hàng thành công.");
    }
}
