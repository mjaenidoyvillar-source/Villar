@extends('layouts.app')

@section('title', $category->name . ' - Stuff\'d')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-tag me-2"></i>{{ $category->name }}</h1>
    <div class="btn-group">
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-1"></i>Edit
        </a>
        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger" 
                    onclick="return confirm('Are you sure you want to delete this category? This will also delete all associated products.')">
                <i class="fas fa-trash me-1"></i>Delete
            </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Category Details</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Name:</dt>
                    <dd class="col-sm-9">{{ $category->name }}</dd>
                    
                    <dt class="col-sm-3">Description:</dt>
                    <dd class="col-sm-9">{{ $category->description ?: 'No description provided.' }}</dd>
                    
                    <dt class="col-sm-3">Created:</dt>
                    <dd class="col-sm-9">{{ $category->created_at->format('M d, Y \a\t g:i A') }}</dd>
                    
                    <dt class="col-sm-3">Updated:</dt>
                    <dd class="col-sm-9">{{ $category->updated_at->format('M d, Y \a\t g:i A') }}</dd>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Statistics</h5>
            </div>
            <div class="card-body text-center">
                <h3 class="text-primary">{{ $category->products->count() }}</h3>
                <p class="text-muted mb-0">Total Products</p>
            </div>
        </div>
    </div>
</div>

@if($category->products->count() > 0)
    <div class="mt-4">
        <h4>Products in this Category</h4>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($category->products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>
                                <span class="badge bg-{{ $product->quantity > 10 ? 'success' : ($product->quantity > 0 ? 'warning' : 'danger') }}">
                                    {{ $product->quantity }}
                                </span>
                            </td>
                            <td>₱{{ number_format($product->price, 2) }}</td>
                            <td>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="mt-4 text-center py-4">
        <i class="fas fa-box fa-2x text-muted mb-3"></i>
        <h5 class="text-muted">No products in this category</h5>
        <p class="text-muted">Products assigned to this category will appear here.</p>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add Product
        </a>
    </div>
@endif

<div class="mt-4">
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to Categories
    </a>
</div>
@endsection
