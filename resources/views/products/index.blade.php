@extends('layouts.app')

@section('title', 'Products - Stuff\'d')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-box me-2"></i>Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Add Product
    </a>
</div>

<!-- Product Statistics -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-primary">
            <div class="card-body text-center">
                <h4 class="card-title text-primary">{{ $stats['total_products'] }}</h4>
                <p class="card-text text-muted">Total Products</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-success">
            <div class="card-body text-center">
                <h4 class="card-title text-success">{{ $stats['in_stock_products'] }}</h4>
                <p class="card-text text-muted">In Stock</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-warning">
            <div class="card-body text-center">
                <h4 class="card-title text-warning">{{ $stats['low_stock_products'] }}</h4>
                <p class="card-text text-muted">Low Stock</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-danger">
            <div class="card-body text-center">
                <h4 class="card-title text-danger">{{ $stats['out_of_stock_products'] }}</h4>
                <p class="card-text text-muted">Out of Stock</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Products</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Search Products</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ $searchQuery }}" placeholder="Enter product name...">
            </div>
            <div class="col-md-2">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category">
                    <option value="all" {{ $categoryFilter === 'all' ? 'selected' : '' }}>All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $categoryFilter == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="stock" class="form-label">Stock Status</label>
                <select class="form-select" id="stock" name="stock">
                    <option value="" {{ $stockFilter === '' ? 'selected' : '' }}>All Stock Levels</option>
                    <option value="in_stock" {{ $stockFilter === 'in_stock' ? 'selected' : '' }}>In Stock (>10)</option>
                    <option value="low_stock" {{ $stockFilter === 'low_stock' ? 'selected' : '' }}>Low Stock (1-10)</option>
                    <option value="out_of_stock" {{ $stockFilter === 'out_of_stock' ? 'selected' : '' }}>Out of Stock (0)</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="recent" class="form-label">Recently Added</label>
                <select class="form-select" id="recent" name="recent">
                    <option value="" {{ $recentFilter === '' ? 'selected' : '' }}>All Time</option>
                    <option value="today" {{ $recentFilter === 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ $recentFilter === 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ $recentFilter === 'month' ? 'selected' : '' }}>This Month</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($products->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            <strong>{{ $product->name }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $product->category->name }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $product->quantity > 10 ? 'success' : ($product->quantity > 0 ? 'warning' : 'danger') }}">
                                {{ $product->quantity }}
                            </span>
                        </td>
                        <td>{{ $product->formatted_price }}</td>
                        <td>{{ $product->formatted_total_value }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 pt-3 border-top">
        <div class="d-flex justify-content-center">
            {{ $products->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h3 class="text-muted">No products found</h3>
        @if($searchQuery || $categoryFilter !== 'all' || $stockFilter || $recentFilter)
            <p class="text-muted">Try adjusting your filters or <a href="{{ route('products.index') }}">clear all filters</a>.</p>
        @else
            <p class="text-muted">Get started by creating your first product.</p>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Create Product
            </a>
        @endif
    </div>
@endif
@endsection
