@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column justify-content-center align-items-center vh-100 bg-light">
        <div class="d-flex justify-content-center align-items-center bg-danger bg-opacity-10 rounded-circle" style="width: 80px; height: 80px;">
            <i class="fas fa-exclamation-triangle text-danger fs-1"></i>
        </div>
        <h1 class="mt-4 fw-bold text-dark">403</h1>
        <p class="lead text-muted">Access Denied</p>
        <p class="text-secondary">
            Sorry, you don't have permission to view this page.
        </p>

        <a href="{{ request()->is('admin', 'admin/*') ? route('admin.dashboard.index') : route('shop.dashboard.index') }}" class="btn btn-primary mt-4 px-4 py-2">
            Back to Dashboard
        </a>

        <div class="mt-5">
            <img src="https://via.placeholder.com/500x300?text=403+Illustration" alt="403 Illustration" class="img-fluid rounded">
        </div>
    </div>
@endsection
@push('css')
    <style>
        .bg-opacity-10 {
            background-color: rgba(255, 0, 0, 0.1) !important;
        }
    </style>
@endpush
