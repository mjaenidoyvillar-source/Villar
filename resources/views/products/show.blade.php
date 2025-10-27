@extends('layouts.app')

@section('title', $product->name . ' - Stuff\'d')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-box me-2"></i>{{ $product->name }}</h1>
    <div class="btn-group">
        <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-1"></i>Edit
        </a>
        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger" 
                    onclick="return confirm('Are you sure you want to delete this product?')">
                <i class="fas fa-trash me-1"></i>Delete
            </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Product Details</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Name:</dt>
                    <dd class="col-sm-9">{{ $product->name }}</dd>
                    
                    <dt class="col-sm-3">Category:</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-secondary">{{ $product->category->name }}</span>
                    </dd>
                    
                    <dt class="col-sm-3">Quantity:</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-{{ $product->quantity > 10 ? 'success' : ($product->quantity > 0 ? 'warning' : 'danger') }} fs-6">
                            {{ $product->quantity }}
                        </span>
                    </dd>
                    
                    <dt class="col-sm-3">Price:</dt>
                    <dd class="col-sm-9">₱{{ number_format($product->price, 2) }}</dd>
                    
                    <dt class="col-sm-3">Total Value:</dt>
                    <dd class="col-sm-9">
                        <strong class="text-primary">₱{{ number_format($product->price * $product->quantity, 2) }}</strong>
                    </dd>
                    
                    <dt class="col-sm-3">Created:</dt>
                    <dd class="col-sm-9">{{ $product->created_at->format('M d, Y \a\t g:i A') }}</dd>
                    
                    <dt class="col-sm-3">Updated:</dt>
                    <dd class="col-sm-9">{{ $product->updated_at->format('M d, Y \a\t g:i A') }}</dd>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Inventory Status</h5>
            </div>
            <div class="card-body text-center">
                @if($product->quantity > 10)
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 class="text-success">In Stock</h5>
                    <p class="text-muted mb-0">Good inventory level</p>
                @elseif($product->quantity > 0)
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5 class="text-warning">Low Stock</h5>
                    <p class="text-muted mb-0">Consider restocking</p>
                @else
                    <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                    <h5 class="text-danger">Out of Stock</h5>
                    <p class="text-muted mb-0">Urgent restock needed</p>
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>Edit Product
                    </a>
                    <a href="{{ route('categories.show', $product->category) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-tag me-1"></i>View Category
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to Products
    </a>
</div>
@endsection
