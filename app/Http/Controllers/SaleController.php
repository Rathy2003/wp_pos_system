<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:cash,card',
            'total_amount' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Create the sale
            $sale = Sale::create([
                'customer_name' => $validated['customer_name'],
                'payment_method' => $validated['payment_method'],
                'total_amount' => $validated['total_amount'],
                'tax_amount' => $validated['tax_amount'],
                'net_amount' => $validated['net_amount'],
                'loyalty_points' => floor($validated['total_amount']), // 1 point per dollar
            ]);

            // Create sale items and update product stock
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['id']);
                
                // Check stock availability
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);

                // Update product stock
                $product->update([
                    'stock' => $product->stock - $item['quantity']
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Sale completed successfully', 'sale' => $sale]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function index()
    {
        return Sale::with('items.product')->latest()->get();
    }
} 