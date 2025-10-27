@extends('layouts.app')

@section('title', 'Transaction #' . $transaction->id . ' - Stuff\'d')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-shopping-cart me-2"></i>Transaction #{{ $transaction->id }}</h1>
    <div class="btn-group">
        @if($transaction->status === 'pending')
            <form action="{{ route('transactions.complete', $transaction) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success" 
                        onclick="return confirm('Complete this transaction and update stock?')">
                    <i class="fas fa-check me-1"></i>Complete
                </button>
            </form>
            <form action="{{ route('transactions.cancel', $transaction) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning" 
                        onclick="return confirm('Cancel this transaction?')">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
            </form>
        @endif
        @if($transaction->status !== 'completed')
            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" 
                        onclick="return confirm('Delete this transaction?')">
                    <i class="fas fa-trash me-1"></i>Delete
                </button>
            </form>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Transaction Details</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Transaction ID:</dt>
                    <dd class="col-sm-9">#{{ $transaction->id }}</dd>
                    
                    <dt class="col-sm-3">Status:</dt>
                    <dd class="col-sm-9">
                        @if($transaction->status === 'completed')
                            <span class="badge bg-success fs-6">Completed</span>
                        @elseif($transaction->status === 'pending')
                            <span class="badge bg-warning fs-6">Pending</span>
                        @else
                            <span class="badge bg-danger fs-6">Cancelled</span>
                        @endif
                    </dd>
                    
                    <dt class="col-sm-3">Buyer:</dt>
                    <dd class="col-sm-9">
                        @if($transaction->buyer)
                            <div>
                                <strong>{{ $transaction->buyer->name }}</strong><br>
                                <small class="text-muted">{{ $transaction->buyer->email }}</small>
                                <br><a href="{{ route('buyers.show', $transaction->buyer) }}" class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="fas fa-user me-1"></i>View Buyer Profile
                                </a>
                            </div>
                        @else
                            <div>
                                <strong>{{ $transaction->buyer_name }}</strong><br>
                                <small class="text-muted">{{ $transaction->buyer_email }}</small>
                                <br><small class="text-warning">Legacy transaction - buyer not in system</small>
                            </div>
                        @endif
                    </dd>
                    
                    
                    <dt class="col-sm-3">Product:</dt>
                    <dd class="col-sm-9">
                        <strong>{{ $transaction->product->name }}</strong><br>
                        <small class="text-muted">{{ $transaction->product->category->name }}</small>
                    </dd>
                    
                    <dt class="col-sm-3">Quantity:</dt>
                    <dd class="col-sm-9">{{ $transaction->quantity_purchased }}</dd>
                    
                    <dt class="col-sm-3">Unit Price:</dt>
                    <dd class="col-sm-9">₱{{ number_format($transaction->unit_price, 2) }}</dd>
                    
                    <dt class="col-sm-3">Total Amount:</dt>
                    <dd class="col-sm-9">
                        <strong class="text-primary fs-5">₱{{ number_format($transaction->total_amount, 2) }}</strong>
                    </dd>
                    
                    @if($transaction->notes)
                        <dt class="col-sm-3">Notes:</dt>
                        <dd class="col-sm-9">{{ $transaction->notes }}</dd>
                    @endif
                    
                    <dt class="col-sm-3">Created:</dt>
                    <dd class="col-sm-9">{{ $transaction->created_at->format('M d, Y \a\t g:i A') }}</dd>
                    
                    <dt class="col-sm-3">Updated:</dt>
                    <dd class="col-sm-9">{{ $transaction->updated_at->format('M d, Y \a\t g:i A') }}</dd>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Product Information</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-5">Product:</dt>
                    <dd class="col-sm-7">{{ $transaction->product->name }}</dd>
                    
                    <dt class="col-sm-5">Category:</dt>
                    <dd class="col-sm-7">{{ $transaction->product->category->name }}</dd>
                    
                    <dt class="col-sm-5">Current Stock:</dt>
                    <dd class="col-sm-7">
                        <span class="badge bg-{{ $transaction->product->quantity > 10 ? 'success' : ($transaction->product->quantity > 0 ? 'warning' : 'danger') }}">
                            {{ $transaction->product->quantity }}
                        </span>
                    </dd>
                    
                    <dt class="col-sm-5">Current Price:</dt>
                    <dd class="col-sm-7">₱{{ number_format($transaction->product->price, 2) }}</dd>
                </dl>
                
                <div class="d-grid">
                    <a href="{{ route('products.show', $transaction->product) }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye me-1"></i>View Product
                    </a>
                </div>
            </div>
        </div>
        
        @if($transaction->status === 'pending')
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('transactions.complete', $transaction) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" 
                                    onclick="return confirm('Complete this transaction and update stock?')">
                                <i class="fas fa-check me-1"></i>Complete Transaction
                            </button>
                        </form>
                        
                        <form action="{{ route('transactions.cancel', $transaction) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100" 
                                    onclick="return confirm('Cancel this transaction?')">
                                <i class="fas fa-times me-1"></i>Cancel Transaction
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to Transactions
    </a>
</div>
@endsection
