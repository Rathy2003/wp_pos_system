@extends('layouts.admin')

@section('title', 'View Customer')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Customer Details</h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <strong>Name:</strong> {{ $customer->name }}
        </div>
        <div class="mb-3">
            <strong>Email:</strong> {{ $customer->email }}
        </div>
        <div class="mb-3">
            <strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}
        </div>
        <div class="mb-3">
            <strong>Address:</strong> {{ $customer->address ?? 'N/A' }}
        </div>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Back to List</a>
        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-primary">Edit</a>
    </div>
</div>
@endsection 