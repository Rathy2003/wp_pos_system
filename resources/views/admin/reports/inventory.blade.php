@extends('layouts.admin')

@section('title', 'Inventory Report')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Inventory Report</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Inventory Report</li>
                </ol>
            </nav>
        </div>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print me-2"></i>Print Report
        </button>
    </div>

    <!-- Summary Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="stats-title">Total Products</h6>
                        <h3 class="stats-value">{{ number_format($summary['total_products']) }}</h3>
                    </div>
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="stats-title">Total Value</h6>
                        <h3 class="stats-value">${{ number_format($summary['total_value'], 2) }}</h3>
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
                        <h6 class="stats-title">Out of Stock</h6>
                        <h3 class="stats-value">{{ number_format($summary['out_of_stock']) }}</h3>
                    </div>
                    <div class="stats-icon bg-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="stats-title">Low Stock</h6>
                        <h3 class="stats-value">{{ number_format($summary['low_stock']) }}</h3>
                    </div>
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-battery-quarter"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Breakdown -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Category Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Products</th>
                                    <th class="text-end">Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($summary['categories'] as $category => $data)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">{{ $category }}</span>
                                    </td>
                                    <td class="text-end">{{ number_format($data['count']) }}</td>
                                    <td class="text-end">${{ number_format($data['value'], 2) }}</td>
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
                    <h5 class="card-title mb-0">Stock Status Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="stockChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Product Inventory</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th class="text-end">Stock</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Total Value</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->image)
                                        <img 
                                            src="{{ asset('images/'.$product->image) }}" 
                                            alt="{{ $product->name }}" 
                                            class="rounded"
                                            width="40"
                                            height="40"
                                            style="object-fit: cover;"
                                        >
                                    @else
                                        <div class="rounded bg-light" style="width: 40px; height: 40px;"></div>
                                    @endif
                                    <div class="ms-3">
                                        <div class="fw-semibold">{{ $product->name }}</div>
                                        <small class="text-muted">{{ $product->code }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $product->category->name }}</span>
                            </td>
                            <td class="text-end">{{ number_format($product->stock) }}</td>
                            <td class="text-end">${{ number_format($product->price, 2) }}</td>
                            <td class="text-end">${{ number_format($product->stock * $product->price, 2) }}</td>
                            <td>
                                @if($product->stock_status === 'out_of_stock')
                                    <span class="badge bg-danger">Out of Stock</span>
                                @elseif($product->stock_status === 'low_stock')
                                    <span class="badge bg-warning">Low Stock</span>
                                @else
                                    <span class="badge bg-success">In Stock</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No products found</td>
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
    const summary = @json($summary);
    
    new Chart(document.getElementById('stockChart'), {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [
                    summary.in_stock,
                    summary.low_stock,
                    summary.out_of_stock
                ],
                backgroundColor: ['#48bb78', '#ecc94b', '#f56565']
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