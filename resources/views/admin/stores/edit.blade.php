@extends('layouts.admin')

@section('title', 'Edit Store')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Edit Store</h5>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('stores.update', $store->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $store->name }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control">{{ $store->description }}</textarea>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" class="form-control">{{ $store->address }}</textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="1" {{ $store->status ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$store->status ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="logo" class="form-label">Logo</label>
                <input type="file" name="logo" id="logo" accept="image/jpeg, image/png, image/jpg, image/webp" class="form-control">
                @if($store->logo)
                    <img src="{{ asset('stores/' . $store->logo) }}" alt="Store Logo" class="img-fluid mt-2" style="width: 150px;height: 150px;object-fit: cover;border-radius: 5px;">
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Update Store</button>
        </form>
    </div>
</div>
@endsection 