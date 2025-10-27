@extends('layouts.app')

@section('title', 'Buyers - Stuff\'d Inventory Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-users text-primary me-2"></i>Buyers
    </h1>
    <a href="{{ route('buyers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Add New Buyer
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($buyers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Transactions</th>
                            <th>Total Spent</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buyers as $buyer)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ strtoupper(substr($buyer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $buyer->name }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $buyer->email }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $buyer->transactions_count }}</span>
                                </td>
                                <td>
                                    <strong class="text-success">
                                        ₱{{ number_format($buyer->transactions_sum_total_amount ?? 0, 2) }}
                                    </strong>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('buyers.show', $buyer) }}" class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('buyers.edit', $buyer) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('buyers.destroy', $buyer) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this buyer?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
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
            <div class="d-flex justify-content-center mt-4">
                {{ $buyers->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No buyers found</h4>
                <p class="text-muted">Start by adding your first buyer to the system.</p>
                <a href="{{ route('buyers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add First Buyer
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
    font-weight: bold;
}
</style>
@endsection
