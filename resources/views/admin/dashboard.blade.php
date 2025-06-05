@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Sales -->
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="stats-title">Total Sales</h6>
                        <h3 class="stats-value">{{ number_format($totalSales) }}</h3>
                        <p class="stats-subtitle mb-0">
                            <span class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                {{ $todaySales }} today
                            </span>
                        </p>
                    </div>
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="stats-title">Total Revenue</h6>
                        <h3 class="stats-value">${{ number_format($totalRevenue, 2) }}</h3>
                        <p class="stats-subtitle mb-0">
                            <span class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                ${{ number_format($todayRevenue, 2) }} today
                            </span>
                        </p>
                    </div>
                    <div class="stats-icon bg-success">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="stats-title">Total Products</h6>
                        <h3 class="stats-value">{{ number_format($totalProducts) }}</h3>
                        <p class="stats-subtitle mb-0">
                            <span class="text-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $stockAlerts }} low stock
                            </span>
                        </p>
                    </div>
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="stats-title">Total Customers</h6>
                        <h3 class="stats-value">{{ number_format($totalCustomers) }}</h3>
                        <p class="stats-subtitle mb-0">Unique customers</p>
                    </div>
                    <div class="stats-icon bg-info">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->hasRole('admin'))
        <div class="row g-4 mb-4">
            <!-- Sales Chart -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Sales Overview</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Payment Methods</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="paymentChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <!-- Recent Sales -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Recent Sales</h5>
                        <a href="{{ route('admin.sales') }}" class="btn btn-primary btn-sm">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Products</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                        <th>Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSales as $sale)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.sales.show', $sale->id) }}">#{{ $sale->id }}</a>
                                        </td>
                                        <td>{{ $sale->customer_name ?? 'Walk-in Customer' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $sale->items_count }} items</span>
                                        </td>
                                        <td>${{ number_format($sale->total_amount, 2) }}</td>
                                        <td>{{ $sale->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            @if($sale->payment_method === 'cash')
                                                <span class="badge bg-success">💵 Cash</span>
                                            @else
                                                <span class="badge bg-primary">💳 Card</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products & Low Stock -->
            <div class="col-xl-4">
                <!-- Top Products -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Top Selling Products</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($topProducts as $product)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $product->name }}</h6>
                                        <small class="text-muted">{{ number_format($product->total_quantity) }} units sold</small>
                                    </div>
                                    <h6 class="text-success mb-0">${{ number_format($product->total_revenue, 2) }}</h6>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Low Stock Products -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Low Stock Alert</h5>
                        <a href="{{ route('admin.products') }}" class="btn btn-primary btn-sm">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($lowStockProducts as $product)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $product->name }}</h6>
                                        <small class="text-muted">{{ $product->category->name }}</small>
                                    </div>
                                    <span class="badge bg-danger">{{ $product->stock }} left</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

  

   
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
        margin-bottom: 0.25rem;
    }

    .stats-subtitle {
        font-size: 0.875rem;
        color: #6c757d;
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

    .card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background: white;
        border-bottom: 1px solid rgba(0,0,0,.125);
        padding: 1.25rem 1.5rem;
    }

    .card-title {
        margin-bottom: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .list-group-item {
        padding: 1rem 1.5rem;
    }

    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
    }

    .bg-primary { background-color: #4299e1 !important; }
    .bg-success { background-color: #48bb78 !important; }
    .bg-warning { background-color: #ed8936 !important; }
    .bg-info { background-color: #4fd1c5 !important; }
    .bg-danger { background-color: #f56565 !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($lastWeekSales->pluck('date')) !!},
            datasets: [{
                label: 'Sales',
                data: {!! json_encode($lastWeekSales->pluck('count')) !!},
                borderColor: '#4299e1',
                tension: 0.4,
                fill: false
            }, {
                label: 'Revenue',
                data: {!! json_encode($lastWeekSales->pluck('revenue')) !!},
                borderColor: '#48bb78',
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Payment Methods Chart
    const paymentCtx = document.getElementById('paymentChart').getContext('2d');
    new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: ['Cash', 'Card'],
            datasets: [{
                data: [
                    {{ $paymentStats['cash'] ?? 0 }},
                    {{ $paymentStats['card'] ?? 0 }}
                ],
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