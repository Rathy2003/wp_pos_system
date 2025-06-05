@extends('layouts.admin')

@section('title', 'Add Store')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Add New Store</h5>
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
        <form action="{{ route('stores.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" class="form-control">{{ old('address') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="" disabled selected>Select Status</option>
                    <option value="1" {{ old('status') && old('status') == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status') && old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="logo" class="form-label">Logo</label>
                <input type="file" name="logo" id="logo" accept="image/jpeg, image/png, image/jpg, image/webp" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Save Store</button>
        </form>
    </div>
</div>
@endsection 