@extends('layouts.admin')

@section('title', 'Sales Report')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Sales Report</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Sales Report</li>
                </ol>
            </nav>
        </div>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print me-2"></i>Print Report
        </button>
    </div>

    <!-- Filters -->
    <div class="card mb-4 no-print">
        <div class="card-body">
            <form action="{{ route('admin.reports.sales') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Date Range</label>
                    <div class="input-group">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        <span class="input-group-text">to</span>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select">
                        <option value="">All Methods</option>
                        <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
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
        <div class="col-md-3">
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
        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="stats-title">Total Tax</h6>
                        <h3 class="stats-value">${{ number_format($summary['total_tax'], 2) }}</h3>
                    </div>
                    <div class="stats-icon bg-info">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="stats-title">Average Sale</h6>
                        <h3 class="stats-value">${{ number_format($summary['avg_sale_value'], 2) }}</h3>
                    </div>
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Methods</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Method</th>
                                    <th class="text-end">Sales Count</th>
                                    <th class="text-end">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($summary['payment_methods'] as $method => $data)
                                <tr>
                                    <td>
                                        <span class="badge {{ $method === 'cash' ? 'bg-success' : 'bg-primary' }}">
                                            {{ $method === 'cash' ? '💵 Cash' : '💳 Card' }}
                                        </span>
                                    </td>
                                    <td class="text-end">{{ number_format($data['count']) }}</td>
                                    <td class="text-end">${{ number_format($data['amount'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Detailed Sales</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th class="text-end">Tax</th>
                            <th class="text-end">Total</th>
                            <th>Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr>
                            <td>#{{ $sale->id }}</td>
                            <td>{{ $sale->created_at->format('M d, Y H:i') }}</td>
                            <td>{{ $sale->customer_name ?? 'Walk-in Customer' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $sale->items_count }} items</span>
                            </td>
                            <td class="text-end">${{ number_format($sale->tax_amount, 2) }}</td>
                            <td class="text-end">${{ number_format($sale->total_amount, 2) }}</td>
                            <td>
                                <span class="badge {{ $sale->payment_method === 'cash' ? 'bg-success' : 'bg-primary' }}">
                                    {{ $sale->payment_method === 'cash' ? '💵 Cash' : '💳 Card' }}
                                </span>
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

    @media print {
        .no-print {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .stats-card {
            box-shadow: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = @json($summary['payment_methods']);
    
    new Chart(document.getElementById('paymentChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(paymentMethods).map(method => 
                method === 'cash' ? '💵 Cash' : '💳 Card'
            ),
            datasets: [{
                data: Object.values(paymentMethods).map(data => data.amount),
                backgroundColor: ['#48bb78', '#4299e1']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush 