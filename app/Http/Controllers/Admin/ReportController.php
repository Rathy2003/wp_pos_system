<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Category;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function sales(Request $request)
    {
        $query = Sale::with(['items.product'])
            ->where('store_id', auth()->user()->store_id)
            ->withCount('items')
            ->latest();

        // Date range filter
        if ($request->filled(['start_date', 'end_date'])) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        // Payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $sales = $query->get();

        // Calculate summary
        $summary = [
            'total_sales' => $sales->count(),
            'total_revenue' => $sales->sum('total_amount'),
            'total_tax' => $sales->sum('tax_amount'),
            'total_items' => $sales->sum('items_count'),
            'avg_sale_value' => $sales->avg('total_amount'),
            'payment_methods' => $sales->groupBy('payment_method')
                ->map(fn($group) => [
                    'count' => $group->count(),
                    'amount' => $group->sum('total_amount')
                ])
        ];

        return view('admin.reports.sales', compact('sales', 'summary'));
    }

    public function products(Request $request)
    {
        $query = SaleItem::with(['product.category'])
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->groupBy('product_id');

        // Date range filter
        if ($request->filled(['start_date', 'end_date'])) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereHas('sale', function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        $productSales = $query->get();
        $categories = Category::where('status', true)->get();

        // Calculate summary
        $summary = [
            'total_quantity' => $productSales->sum('total_quantity'),
            'total_revenue' => $productSales->sum('total_revenue'),
            'avg_price' => $productSales->avg('total_revenue'),
            'categories' => $productSales->groupBy('product.category.name')
                ->map(fn($group) => [
                    'quantity' => $group->sum('total_quantity'),
                    'revenue' => $group->sum('total_revenue')
                ])
        ];

        return view('admin.reports.products', compact('productSales', 'summary', 'categories'));
    }

    public function inventory()
    {
        $products = Product::with('category')
            ->where('store_id', auth()->user()->store_id)
            ->select('*')
            ->selectRaw('CASE 
                WHEN stock = 0 THEN "out_of_stock"
                WHEN stock <= 10 THEN "low_stock"
                ELSE "in_stock"
            END as stock_status')
            ->get();


        $summary = [
            'total_products' => $products->count(),
            'total_value' => $products->sum(fn($p) => $p->stock * $p->price),
            'out_of_stock' => $products->where('stock_status', 'out_of_stock')->count(),
            'low_stock' => $products->where('stock_status', 'low_stock')->count(),
            'in_stock' => $products->where('stock_status', 'in_stock')->count(),
            'categories' => $products->groupBy('category.name')
                ->map(fn($group) => [
                    'count' => $group->count(),
                    'value' => $group->sum(fn($p) => $p->stock * $p->price)
                ])
        ];

        return view('admin.reports.inventory', compact('products', 'summary'));
    }
} 