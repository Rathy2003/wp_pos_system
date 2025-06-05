@extends('layouts.admin')

@section('title', 'Add New Category')

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

    textarea.form-control {
        height: auto;
        min-height: 100px;
    }
    
    .form-check-input:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
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
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="card-title">Add New Category</h5>
                        <p class="text-muted">Create a new category for your products</p>
                    </div>

                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Category Name</label>
                                    <input 
                                        type="text" 
                                        name="name" 
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}"
                                        required
                                        autofocus
                                    >
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Description</label>
                                    <textarea 
                                        name="description" 
                                        class="form-control @error('description') is-invalid @enderror"
                                        rows="4"
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <input 
                                        type="checkbox" 
                                        name="status" 
                                        class="form-check-input" 
                                        id="statusSwitch"
                                        {{ old('status', true) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="statusSwitch">Active</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr class="my-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.categories') }}" class="btn btn-light">
                                        <i class="fas fa-arrow-left"></i>
                                        Back
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i>
                                        Save Category
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