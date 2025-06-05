@extends('layouts.admin')

@section('title', 'Sales')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Sales</h1>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="stats-title">Total Sales</h6>
                        <h3 class="stats-value">{{ number_format($summary['total_sales']) }}</h3>
                    </div>
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="stats-title">Total Revenue</h6>
                        <h3 class="stats-value">${{ number_format($summary['total_revenue'], 2) }}</h3>
                    </div>
                    <div class="stats-icon bg-success">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="stats-title">Average Sale Value</h6>
                        <h3 class="stats-value">${{ number_format($summary['avg_sale_value'], 2) }}</h3>
                    </div>
                    <div class="stats-icon bg-info">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="stats-title">Total Items Sold</h6>
                        <h3 class="stats-value">{{ number_format($summary['total_items']) }}</h3>
                    </div>
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.sales') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date Range</label>
                    <div class="input-group">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        <span class="input-group-text">to</span>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select">
                        <option value="">All</option>
                        <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Customer Name</label>
                    <input type="text" name="customer" class="form-control" value="{{ request('customer') }}" placeholder="Search customer...">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Amount Range</label>
                    <div class="input-group">
                        <input type="number" name="min_amount" class="form-control" value="{{ request('min_amount') }}" placeholder="Min">
                        <span class="input-group-text">to</span>
                        <input type="number" name="max_amount" class="form-control" value="{{ request('max_amount') }}" placeholder="Max">
                    </div>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total Amount</th>
                            <th>Payment Method</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>#{{ $sale->id }}</td>
                                <td>{{ $sale->customer_name ?? 'Walk-in Customer' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $sale->items_count }} items</span>
                                </td>
                                <td>${{ number_format($sale->total_amount, 2) }}</td>
                                <td>
                                    @if($sale->payment_method === 'cash')
                                        <span class="badge bg-success">💵 Cash</span>
                                    @else
                                        <span class="badge bg-primary">💳 Card</span>
                                    @endif
                                </td>
                                <td>{{ $sale->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.sales.show', $sale) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No sales found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $sales->firstItem() ?? 0 }} to {{ $sales->lastItem() ?? 0 }} of {{ $sales->total() }} results
                </div>
                <div>
                    {{ $sales->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stats-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .stats-title {
        color: #6c757d;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .stats-value {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0;
    }

    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stats-icon i {
        font-size: 1.5rem;
        color: white;
    }

    /* Pagination Styles */
    .pagination {
        margin-bottom: 0;
    }

    .page-link {
        padding: 0.375rem 0.75rem;
        color: #4a5568;
        background-color: #fff;
        border: 1px solid #e2e8f0;
    }

    .page-link:hover {
        color: #2d3748;
        background-color: #edf2f7;
        border-color: #e2e8f0;
    }

    .page-item.active .page-link {
        color: #fff;
        background-color: #4299e1;
        border-color: #4299e1;
    }

    .page-item.disabled .page-link {
        color: #a0aec0;
        background-color: #fff;
        border-color: #e2e8f0;
    }
</style>
@endpush 