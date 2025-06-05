<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic statistics
        $totalSales = Sale::count();
        $totalRevenue = Sale::sum('total_amount');
        $totalProducts = Product::count();
        $totalCustomers = Sale::distinct('customer_name')->whereNotNull('customer_name')->count();

        // Today's statistics
        $today = Carbon::today();
        $todaySales = Sale::whereDate('created_at', $today)->count();
        $todayRevenue = Sale::whereDate('created_at', $today)->sum('total_amount');

        // Get sales for the last 7 days
        $lastWeekSales = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(total_amount) as revenue')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get top selling products
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // Get sales by category
        $salesByCategory = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.subtotal) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        // Get payment method statistics
        $paymentStats = Sale::select('payment_method', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get()
            ->pluck('count', 'payment_method')
            ->toArray();

        // Get recent sales with items and customer info
        $recentSales = Sale::with(['items.product'])
            ->withCount('items')
            ->latest()
            ->take(5)
            ->get();

        // Get low stock products with category info
        $lowStockProducts = Product::with('category')
            ->where('stock', '<', 10)
            ->latest()
            ->take(5)
            ->get();

        // Get stock alerts count
        $stockAlerts = Product::where('stock', '<', 10)->count();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalRevenue',
            'totalProducts',
            'totalCustomers',
            'todaySales',
            'todayRevenue',
            'lastWeekSales',
            'topProducts',
            'salesByCategory',
            'paymentStats',
            'recentSales',
            'lowStockProducts',
            'stockAlerts'
        ));
    }
} 