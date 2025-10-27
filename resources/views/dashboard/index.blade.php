@extends('layouts.app')

@section('title', 'Dashboard - Stuff\'d')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add Product
        </a>
        <a href="{{ route('transactions.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-shopping-cart me-1"></i>New Transaction
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-2 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h4 class="card-title">{{ $stats['total_products'] }}</h4>
                <p class="card-text">Total Products</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h4 class="card-title">{{ $stats['in_stock_products'] }}</h4>
                <p class="card-text">In Stock</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h4 class="card-title">{{ $stats['low_stock_products'] }}</h4>
                <p class="card-text">Low Stock</p>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h4 class="card-title">{{ $stats['out_of_stock_products'] }}</h4>
                <p class="card-text">Out of Stock</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title text-primary">Inventory Value</h5>
                <h3 class="text-primary">₱{{ number_format($stats['total_inventory_value'], 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title text-success">Total Sales</h5>
                <h3 class="text-success">₱{{ number_format($stats['total_sales_value'], 2) }}</h3>
            </div>
        </div>
    </div>
</div>


@endsection
