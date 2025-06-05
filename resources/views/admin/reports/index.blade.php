@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Reports</h1>
            <p class="text-muted">View detailed reports and analytics</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sales Report Card -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <span class="report-icon bg-primary">
                                <i class="fas fa-chart-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-0">Sales Report</h5>
                            <small class="text-muted">View detailed sales analytics</small>
                        </div>
                    </div>
                    <p class="card-text">
                        Analyze sales performance, revenue trends, and payment methods. Filter by date range and view detailed transaction history.
                    </p>
                    <a href="{{ route('admin.reports.sales') }}" class="btn btn-primary">
                        View Report
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Products Report Card -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <span class="report-icon bg-success">
                                <i class="fas fa-box"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-0">Products Report</h5>
                            <small class="text-muted">View product performance</small>
                        </div>
                    </div>
                    <p class="card-text">
                        Track best-selling products, analyze sales by category, and identify top-performing items.
                    </p>
                    <a href="{{ route('admin.reports.products') }}" class="btn btn-success">
                        View Report
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Inventory Report Card -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <span class="report-icon bg-info">
                                <i class="fas fa-warehouse"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-0">Inventory Report</h5>
                            <small class="text-muted">View stock levels and value</small>
                        </div>
                    </div>
                    <p class="card-text">
                        Monitor inventory levels, track stock value, and identify items that need reordering.
                    </p>
                    <a href="{{ route('admin.reports.inventory') }}" class="btn btn-info">
                        View Report
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .report-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .report-icon i {
        font-size: 1.5rem;
        color: white;
    }

    .card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .btn {
        border-radius: 8px;
        padding: 0.5rem 1rem;
    }

    .btn i {
        transition: transform 0.3s ease;
    }

    .btn:hover i {
        transform: translateX(5px);
    }
</style>
@endpush 