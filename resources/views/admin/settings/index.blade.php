@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Settings</h1>
            <p class="text-muted">Manage your application settings</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">General Settings</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="store_name" class="form-label">Store Name</label>
                    <input type="text" name="store_name" id="store_name" class="form-control" value="{{ $settings->where('key', 'store_name')->first()->value ?? 'Default Store' }}" required>
                </div>
                <div class="mb-3">
                    <label for="currency" class="form-label">Currency</label>
                    <input type="text" name="currency" id="currency" class="form-control" value="{{ $settings->where('key', 'currency')->first()->value ?? 'USD' }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        .form-label { font-weight: 600; }
        .card { border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    </style>
@endpush 