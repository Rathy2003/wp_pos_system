@extends('layouts.admin')

@section('title', 'Products')

@section('styles')
<style>
    .search-wrapper {
        position: relative;
        width: 100%;
    }
    
    .search-wrapper .form-control {
        height: 47px;
        padding-left: 40px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    
    .search-wrapper .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .search-wrapper .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 16px;
        z-index: 10;
        pointer-events: none;
    }

    .form-select {
        height: 47px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-filter {
        height: 47px;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('content')
<div>
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">Products List</h5>
                    <small class="text-muted">Manage your products inventory</small>
                </div>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add New Product
                </a>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.products') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="search-wrapper">
                        <span class="search-icon">
                            <i class="fas fa-search"></i>
                        </span>
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Search by name or code..."
                            value="{{ request('search') }}"
                            autocomplete="off"
                        >
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="stock" class="form-select">
                        <option value="">All Stock Status</option>
                        <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="in" {{ request('stock') == 'in' ? 'selected' : '' }}>In Stock</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 btn-filter">
                        <i class="fas fa-filter me-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Code</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
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
                                            width="70"
                                        >
                                    @else
                                        <img 
                                            src="{{ asset('no-image.png') }}" 
                                            class="rounded"
                                            width="70"
                                        >
                                    @endif
                                    <div class="ms-3">
                                        <div class="fw-semibold">{{ $product->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->code }}</td>
                            <td>
                                <span class="badge" style="background: #4b6cb7;">
                                    {{ $product->category['name'] }}
                                </span>
                            </td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>
                                @if($product->stock > 10)
                                    <span class="badge bg-success">{{ $product->stock }} in stock</span>
                                @elseif($product->stock > 0)
                                    <span class="badge bg-warning">{{ $product->stock }} left</span>
                                @else
                                    <span class="badge bg-danger">Out of stock</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="deleteProduct({{ $product->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $product->id }}"
                                      action="{{ route('admin.products.destroy', $product->id) }}"
                                      method="POST"
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" 
                                     alt="No products found"
                                     width="80"
                                     class="mb-3">
                                <h5 class="text-muted mb-0">No products found</h5>
                                <p class="text-muted">Try adjusting your search or filter to find what you're looking for.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteProduct(productId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + productId).submit();
        }
    });
}

// Show success message if exists
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
@endif
</script>
@endpush 