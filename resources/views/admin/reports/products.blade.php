@extends('layouts.admin')

@section('title', 'Products Report')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Products Report</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Products Report</li>
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
            <form action="{{ route('admin.reports.products') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Date Range</label>
                    <div class="input-group">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        <span class="input-group-text">to</span>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
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
        <div class="col-md-4">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="stats-title">Total Quantity Sold</h6>
                        <h3 class="stats-value">{{ number_format($summary['total_quantity']) }}</h3>
                    </div>
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
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
        <div class="col-md-4">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="stats-title">Average Price</h6>
                        <h3 class="stats-value">${{ number_format($summary['avg_price'], 2) }}</h3>
                    </div>
                    <div class="stats-icon bg-info">
                        <i class="fas fa-tag"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Performance -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Category Performance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Quantity</th>
                                    <th class="text-end">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($summary['categories'] as $category => $data)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">{{ $category }}</span>
                                    </td>
                                    <td class="text-end">{{ number_format($data['quantity']) }}</td>
                                    <td class="text-end">${{ number_format($data['revenue'], 2) }}</td>
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
                    <h5 class="card-title mb-0">Revenue by Category</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Product Performance</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th class="text-end">Quantity Sold</th>
                            <th class="text-end">Revenue</th>
                            <th class="text-end">Average Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productSales as $sale)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($sale->product->image)
                                        <img 
                                            src="{{ asset('images/'.$sale->product->image) }}" 
                                            alt="{{ $sale->product->name }}" 
                                            class="rounded"
                                            width="40"
                                            height="40"
                                            style="object-fit: cover;"
                                        >
                                    @else
                                        <div class="rounded bg-light" style="width: 40px; height: 40px;"></div>
                                    @endif
                                    <div class="ms-3">
                                        <div class="fw-semibold">{{ $sale->product->name }}</div>
                                        <small class="text-muted">{{ $sale->product->code }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $sale->product->category->name }}</span>
                            </td>
                            <td class="text-end">{{ number_format($sale->total_quantity) }}</td>
                            <td class="text-end">${{ number_format($sale->total_revenue, 2) }}</td>
                            <td class="text-end">${{ number_format($sale->total_revenue / $sale->total_quantity, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No product sales found</td>
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
    const categories = @json($summary['categories']);
    
    new Chart(document.getElementById('categoryChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(categories),
            datasets: [{
                data: Object.values(categories).map(data => data.revenue),
                backgroundColor: [
                    '#4299e1', '#48bb78', '#ecc94b', '#ed64a6', 
                    '#667eea', '#9f7aea', '#f56565', '#ed8936'
                ]
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