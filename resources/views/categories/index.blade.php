@extends('layouts.app')

@section('title', 'Categories - Stuff\'d')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-tags me-2"></i>Categories</h1>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Add Category
    </a>
</div>

@if($categories->count() > 0)
    <div class="row">
        @foreach($categories as $category)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text text-muted">
                            {{ $category->description ?: 'No description provided.' }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-box me-1"></i>{{ $category->products->count() }} products
                            </small>
                            <div class="btn-group" role="group">
                                <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this category? This will also delete all associated products.')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $categories->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
        <h3 class="text-muted">No categories found</h3>
        <p class="text-muted">Get started by creating your first category.</p>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Create Category
        </a>
    </div>
@endif
@endsection
