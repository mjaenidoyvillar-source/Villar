@extends('layouts.app')

@section('title', 'Edit Buyer - Stuff\'d Inventory Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-user-edit text-primary me-2"></i>Edit Buyer
    </h1>
    <div>
        <a href="{{ route('buyers.show', $buyer) }}" class="btn btn-outline-info me-2">
            <i class="fas fa-eye me-1"></i>View Details
        </a>
        <a href="{{ route('buyers.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Buyers
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Buyer Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('buyers.update', $buyer) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $buyer->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $buyer->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    
                    
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Buyer
                        </button>
                        <a href="{{ route('buyers.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Buyer Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-1">{{ $buyer->transactions_count }}</h4>
                            <small class="text-muted">Total Transactions</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-1">₱{{ number_format($buyer->transactions_sum_total_amount ?? 0, 0) }}</h4>
                        <small class="text-muted">Total Spent</small>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <span class="badge bg-{{ $buyer->status === 'active' ? 'success' : 'secondary' }} fs-6">
                        {{ ucfirst($buyer->status) }} Buyer
                    </span>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('buyers.show', $buyer) }}" class="btn btn-outline-info">
                        <i class="fas fa-eye me-1"></i>View Details
                    </a>
                    <a href="{{ route('transactions.create') }}?buyer_id={{ $buyer->id }}" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-1"></i>New Transaction
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
