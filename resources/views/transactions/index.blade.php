@extends('layouts.app')

@section('title', 'Transactions - Stuff\'d')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-shopping-cart me-2"></i>Transactions</h1>
    <a href="{{ route('transactions.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>New Transaction
    </a>
</div>

<!-- Financial Statistics -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title text-primary">Inventory Value</h5>
                <h3 class="text-primary">₱{{ number_format($stats['total_inventory_value'], 2) }}</h3>
                <p class="text-muted mb-0">Total value of current stock</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title text-success">Total Sales</h5>
                <h3 class="text-success">₱{{ number_format($stats['total_sales_value'], 2) }}</h3>
                <p class="text-muted mb-0">Completed transactions value</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Transactions</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('transactions.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ $searchQuery }}" placeholder="Buyer name, email, or product...">
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ $statusFilter === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $statusFilter === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="product" class="form-label">Product</label>
                <select class="form-select" id="product" name="product">
                    <option value="all" {{ $productFilter === 'all' ? 'selected' : '' }}>All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $productFilter == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">From Date</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">To Date</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ $dateTo }}">
            </div>
            <div class="col-md-1">
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

@if($transactions->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Buyer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td>#{{ $transaction->id }}</td>
                        <td>
                            <div>
                                @if($transaction->buyer)
                                    <strong>{{ $transaction->buyer->name }}</strong><br>
                                    <small class="text-muted">{{ $transaction->buyer->email }}</small>
                                @else
                                    <strong>{{ $transaction->buyer_name }}</strong><br>
                                    <small class="text-muted">{{ $transaction->buyer_email }}</small>
                                    <br><small class="text-warning">Legacy transaction</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $transaction->product->name }}</strong><br>
                                <small class="text-muted">{{ $transaction->product->category->name }}</small>
                            </div>
                        </td>
                        <td>{{ $transaction->quantity_purchased }}</td>
                        <td>{{ $transaction->formatted_unit_price }}</td>
                        <td><strong>{{ $transaction->formatted_total }}</strong></td>
                        <td>
                            @if($transaction->status === 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($transaction->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </td>
                        <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($transaction->status === 'pending')
                                    <form action="{{ route('transactions.complete', $transaction) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" 
                                                onclick="return confirm('Complete this transaction and update stock?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('transactions.cancel', $transaction) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Cancel this transaction?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                @if($transaction->status !== 'completed')
                                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Delete this transaction?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $transactions->appends(request()->query())->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
        @if($searchQuery || $statusFilter !== 'all' || $productFilter !== 'all' || $dateFrom || $dateTo)
            <h3 class="text-muted">No transactions match your filters</h3>
            <p class="text-muted">Try adjusting your search criteria or <a href="{{ route('transactions.index') }}">clear all filters</a>.</p>
        @else
            <h3 class="text-muted">No transactions found</h3>
            <p class="text-muted">Start by creating a new transaction.</p>
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Create Transaction
            </a>
        @endif
    </div>
@endif
@endsection
