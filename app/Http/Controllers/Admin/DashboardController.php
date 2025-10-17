<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Overview statistics
        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', '!=', 'canceled')->sum('total'),
            'total_products' => Product::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'low_stock_products' => Product::where('stock', '<=', 10)->count(),
        ];

        // Recent orders
        $recentOrders = Order::with('user', 'orderItems.product')
            ->latest()
            ->take(10)
            ->get();

        // Monthly revenue chart data
        $monthlyRevenue = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(total) as revenue')
        )
            ->where('status', '!=', 'canceled')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Top selling products
        $topProducts = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'canceled')
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // Order status distribution
        $orderStatusStats = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Low stock products
        $lowStockProducts = Product::with('category')
            ->where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'monthlyRevenue',
            'topProducts',
            'orderStatusStats',
            'lowStockProducts'
        ));
    }

    public function analytics()
    {
        // Daily sales for the last 30 days
        $dailySales = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as orders'),
            DB::raw('SUM(total) as revenue')
        )
            ->where('status', '!=', 'canceled')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // Category performance
        $categoryPerformance = Category::select(
            'categories.*',
            DB::raw('COUNT(order_items.id) as total_orders'),
            DB::raw('SUM(order_items.quantity * order_items.price) as revenue')
        )
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'canceled')
            ->groupBy('categories.id')
            ->orderBy('revenue', 'desc')
            ->get();

        // Customer statistics
        $customerStats = [
            'new_customers_this_month' => User::where('role', 'customer')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'repeat_customers' => User::where('role', 'customer')
                ->whereHas('orders', function ($query) {
                    $query->havingRaw('COUNT(*) > 1');
                })
                ->count(),
        ];

        return view('admin.analytics', compact(
            'dailySales',
            'categoryPerformance',
            'customerStats'
        ));
    }
}
