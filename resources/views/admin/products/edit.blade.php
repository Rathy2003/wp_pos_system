@extends('layouts.admin')

@section('title', 'Edit Product')

@section('styles')
<style>
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-label {
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #374151;
    }
    
    .form-control, .form-select {
        height: 47px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .input-group-text {
        border-radius: 8px 0 0 8px;
        border: 1px solid #e5e7eb;
        background-color: #f9fafb;
    }
    
    .btn {
        height: 47px;
        padding: 0 20px;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .btn i {
        margin-right: 8px;
    }
    
    .btn-light {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
    }
    
    .btn-light:hover {
        background-color: #f3f4f6;
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.1);
    }

    .product-image-preview {
        width: 150px;
        height: 150px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="card-title">Edit Product</h5>
                        <p class="text-muted">Update the product information below</p>
                    </div>

                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Product Name</label>
                                    <input 
                                        type="text" 
                                        name="name" 
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $product->name) }}"
                                        required
                                    >
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Product Code</label>
                                    <input 
                                        type="text" 
                                        name="code" 
                                        class="form-control @error('code') is-invalid @enderror"
                                        value="{{ old('code', $product->code) }}"
                                        required
                                    >
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input 
                                            type="number" 
                                            name="price" 
                                            class="form-control @error('price') is-invalid @enderror"
                                            value="{{ old('price', $product->price) }}"
                                            step="0.01"
                                            required
                                        >
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Stock</label>
                                    <input 
                                        type="number" 
                                        name="stock" 
                                        class="form-control @error('stock') is-invalid @enderror"
                                        value="{{ old('stock', $product->stock) }}"
                                        required
                                    >
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Category</label>
                                    <select 
                                        name="category_id" 
                                        class="form-select @error('category_id') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Image</label>
                                    <input 
                                        type="file" 
                                        name="image" 
                                        accept="image/*" 
                                        class="form-control @error('image') is-invalid @enderror"
                                    >
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if($product->image)
                            <div class="col-12">
                                <label class="form-label">Current Image</label>
                                <div>
                                    <img 
                                        src="{{ asset('images/'.$product->image) }}" 
                                        alt="{{ $product->name }}" 
                                        class="product-image-preview"
                                    >
                                </div>
                            </div>
                            @endif

                            <div class="col-12">
                                <hr class="my-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.products') }}" class="btn btn-light">
                                        <i class="fas fa-arrow-left"></i>
                                        Back
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i>
                                        Update Product
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Preview image before upload
document.querySelector('input[type="file"]').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.product-image-preview').src = e.target.result;
        }
        reader.readAsDataURL(e.target.files[0]);
    }
});

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