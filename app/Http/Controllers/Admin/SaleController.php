<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['items.product'])
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

        // Customer name search
        if ($request->filled('customer')) {
            $query->where('customer_name', 'like', '%' . $request->customer . '%');
        }

        // Amount range filter
        if ($request->filled(['min_amount', 'max_amount'])) {
            $query->whereBetween('total_amount', [
                $request->min_amount,
                $request->max_amount
            ]);
        }

        $sales = $query->paginate(10)->withQueryString();

        // Get summary statistics
        $summary = [
            'total_sales' => Sale::count(),
            'total_revenue' => Sale::sum('total_amount'),
            'avg_sale_value' => Sale::avg('total_amount'),
            'total_items' => SaleItem::sum('quantity')
        ];

        return view('admin.sales.index', compact('sales', 'summary'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['items.product.category']);
        return view('admin.sales.show', compact('sale'));
    }
} 