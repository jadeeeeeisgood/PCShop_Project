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

        // Check if export is requested
        if ($request->filled('export') && $request->export == '1') {
            return $this->exportReport($request, $query);
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

    /**
     * Mark COD order as delivered and paid
     */
    public function markCodComplete(Order $order)
    {
        if ($order->payment_method !== 'cod') {
            return redirect()->back()->with('error', 'Chỉ có thể xử lý đơn hàng COD.');
        }

        $order->update([
            'status' => 'delivered',
            'payment_status' => 'completed',
            'notes' => ($order->notes ? $order->notes . "\n" : '') . 'Đã giao hàng và thu tiền COD thành công - ' . now()->format('d/m/Y H:i')
        ]);

        try {
            Mail::to($order->customer_email)->send(new OrderStatusUpdate($order));
        } catch (\Exception $e) {
            \Log::error('COD completion email failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Đã đánh dấu đơn hàng COD hoàn thành.');
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
            'status' => 'required|in:processing,shipped,delivered,canceled',
        ]);

        $orders = Order::whereIn('id', $request->order_ids)->get();

        foreach ($orders as $order) {
            if ($order->status !== $request->status) {
                $order->update(['status' => $request->status]);

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

    public function exportReport(Request $request, $query = null)
    {
        if ($query === null) {
            $query = Order::with('user', 'orderItems.product');

            // Apply same filters as index method
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }
            if ($request->filled('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                });
            }
        }

        $orders = $query->latest()->get();

        // Generate CSV export
        $filename = 'orders_report_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding in Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // CSV headers
            fputcsv($file, [
                'ID Đơn hàng',
                'Tên khách hàng',
                'Email',
                'Số điện thoại',
                'Địa chỉ',
                'Tổng tiền (VNĐ)',
                'Phương thức thanh toán',
                'Trạng thái thanh toán',
                'Trạng thái đơn hàng',
                'Số sản phẩm',
                'Ngày đặt hàng',
                'Ghi chú'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone,
                    $order->shipping_address,
                    number_format($order->total, 0, ',', '.'),
                    $this->getPaymentMethodName($order->payment_method),
                    $this->getPaymentStatusName($order->payment_status),
                    $this->getOrderStatusName($order->status),
                    $order->orderItems->count(),
                    $order->created_at->format('d/m/Y H:i'),
                    $order->notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getPaymentMethodName($method)
    {
        return match ($method) {
            'cod' => 'Thanh toán khi nhận hàng',
            'vnpay' => 'VNPay',
            'momo' => 'MoMo',
            default => $method
        };
    }

    private function getPaymentStatusName($status)
    {
        return match ($status) {
            'pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'failed' => 'Thất bại',
            default => $status
        };
    }

    private function getOrderStatusName($status)
    {
        return match ($status) {
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đã gửi',
            'delivered' => 'Hoàn thành',
            'canceled' => 'Đã hủy',
            default => $status
        };
    }

    public function destroy(Order $order)
    {
        // Kiểm tra xem đơn hàng có thể xóa không
        if (in_array($order->status, ['processing', 'shipped', 'delivered'])) {
            return redirect()->back()
                ->with('error', 'Không thể xóa đơn hàng đã được xử lý/gửi/hoàn thành!');
        }

        // Xóa order items trước
        $order->orderItems()->delete();

        // Xóa payment transactions nếu có
        $order->paymentTransactions()->delete();

        // Xóa đơn hàng
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', "Đã xóa đơn hàng #{$order->id} thành công.");
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
        ]);

        $orders = Order::whereIn('id', $request->order_ids)->get();
        $deletedCount = 0;
        $errors = [];

        foreach ($orders as $order) {
            if (in_array($order->status, ['processing', 'shipped', 'delivered'])) {
                $errors[] = "Đơn hàng #{$order->id} không thể xóa (đã được xử lý)";
                continue;
            }

            // Xóa order items và payment transactions
            $order->orderItems()->delete();
            $order->paymentTransactions()->delete();
            $order->delete();
            $deletedCount++;
        }

        $message = "Đã xóa {$deletedCount} đơn hàng thành công.";
        if (!empty($errors)) {
            $message .= " Có " . count($errors) . " đơn hàng không thể xóa.";
        }

        $messageType = empty($errors) ? 'success' : 'warning';

        return redirect()->back()
            ->with($messageType, $message);
    }
}
