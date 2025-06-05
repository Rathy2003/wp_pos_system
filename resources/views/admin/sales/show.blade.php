@extends('layouts.admin')

@section('title', 'Sale Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Sale Details #{{ $sale->id }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.sales') }}">Sales</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sale #{{ $sale->id }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-2"></i>Print
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Sale Information -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sale Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small text-muted">Customer</label>
                        <div class="fw-bold">{{ $sale->customer_name ?? 'Walk-in Customer' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted">Date</label>
                        <div class="fw-bold">{{ $sale->created_at->format('M d, Y H:i') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted">Payment Method</label>
                        <div>
                            @if($sale->payment_method === 'cash')
                                <span class="badge bg-success">💵 Cash Payment</span>
                            @else
                                <span class="badge bg-primary">💳 Card Payment</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted">Loyalty Points Earned</label>
                        <div class="fw-bold">{{ $sale->loyalty_points }} points</div>
                    </div>
                </div>
            </div>

            <!-- Amount Summary -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Amount Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal:</span>
                        <span>${{ number_format($sale->net_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Tax (10%):</span>
                        <span>${{ number_format($sale->tax_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total:</span>
                        <span class="fw-bold">${{ number_format($sale->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->product->category->name }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">${{ number_format($sale->net_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Tax (10%):</strong></td>
                                    <td class="text-end">${{ number_format($sale->tax_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong>${{ number_format($sale->total_amount, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        .sidebar,
        .navbar,
        .breadcrumb,
        .btn-primary {
            display: none !important;
        }
        
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .card-header {
            background: none !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        
        .container-fluid {
            padding: 0 !important;
        }
    }
</style>
@endpush 