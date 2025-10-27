@extends('layouts.app')

@section('title', 'Buyer Details - Stuff\'d Inventory Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-user text-primary me-2"></i>Buyer Details
    </h1>
    <div>
        <a href="{{ route('buyers.edit', $buyer) }}" class="btn btn-outline-primary me-2">
            <i class="fas fa-edit me-1"></i>Edit Buyer
        </a>
        <a href="{{ route('buyers.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Buyers
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                    {{ strtoupper(substr($buyer->name, 0, 1)) }}
                </div>
                <h4 class="card-title">{{ $buyer->name }}</h4>
                <p class="text-muted">{{ $buyer->email }}</p>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Contact Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Email:</strong><br>
                    <a href="mailto:{{ $buyer->email }}" class="text-decoration-none">{{ $buyer->email }}</a>
                </div>
                
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('buyers.edit', $buyer) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>Edit Buyer
                    </a>
                    <a href="{{ route('transactions.create') }}?buyer_id={{ $buyer->id }}" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-1"></i>New Transaction
                    </a>
                    <form action="{{ route('buyers.destroy', $buyer) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this buyer?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-1"></i>Delete Buyer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Purchase Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border-end">
                            <h3 class="text-primary mb-1">{{ $buyer->transactions->count() }}</h3>
                            <small class="text-muted">Total Transactions</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h3 class="text-success mb-1">₱{{ number_format($buyer->transactions->where('status', 'completed')->sum('total_amount'), 0) }}</h3>
                            <small class="text-muted">Total Spent</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h3 class="text-info mb-1">{{ $buyer->transactions->where('status', 'pending')->count() }}</h3>
                            <small class="text-muted">Pending Orders</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h3 class="text-warning mb-1">{{ $buyer->transactions->where('status', 'cancelled')->count() }}</h3>
                        <small class="text-muted">Cancelled Orders</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Transaction History</h5>
                <a href="{{ route('transactions.create') }}?buyer_id={{ $buyer->id }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>New Transaction
                </a>
            </div>
            <div class="card-body">
                @if($buyer->transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($buyer->transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                        <td>{{ $transaction->product->name }}</td>
                                        <td>{{ $transaction->quantity_purchased }}</td>
                                        <td>₱{{ number_format($transaction->unit_price, 2) }}</td>
                                        <td><strong>₱{{ number_format($transaction->total_amount, 2) }}</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-2x text-muted mb-3"></i>
                        <h5 class="text-muted">No transactions yet</h5>
                        <p class="text-muted">This buyer hasn't made any purchases yet.</p>
                        <a href="{{ route('transactions.create') }}?buyer_id={{ $buyer->id }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Create First Transaction
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
    font-size: 32px;
    font-weight: bold;
}
</style>
@endsection
