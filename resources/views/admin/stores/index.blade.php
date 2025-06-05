@extends('layouts.admin')

@section('title', 'Stores')

@section('content')
<div class="container-fluid">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Stores</h5>
                        <small class="text-muted">Manage your stores</small>
                    </div>
                    <a href="{{ route('stores.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Add New Store
                    </a>
                </div>
            </div>
        </div>
    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stores as $store)
                        <tr>
                            <td>{{ $store->id }}</td>
                            <td>
                                @if($store->logo)
                                    <img src="{{ asset('stores/' . $store->logo) }}" alt="{{ $store->name }}" class="img-fluid" style="width: 85px;height: 85px;object-fit: cover;border-radius: 5px;">
                                @else
                                    <span class="text-muted">No logo</span>
                                @endif
                            </td>
                            <td>{{ $store->name }}</td>
                            <td>{{ $store->address }}</td>
                            <td>{{ $store->status ? 'Active' : 'Inactive' }}</td>
                            <td>
                                <a href="{{ route('stores.edit', $store->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('stores.destroy', $store->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 