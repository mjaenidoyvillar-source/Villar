@extends('layouts.app')

@section('title', 'Create Transaction - Stuff\'d')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Create New Transaction</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="buyer_id" class="form-label">Buyer <span class="text-danger">*</span></label>
                        <select class="form-select @error('buyer_id') is-invalid @enderror" 
                                id="buyer_id" name="buyer_id" required>
                            <option value="">Select a buyer</option>
                            @foreach($buyers as $buyer)
                                <option value="{{ $buyer->id }}" 
                                        data-email="{{ $buyer->email }}"
                                        {{ (old('buyer_id') == $buyer->id || $selectedBuyerId == $buyer->id) ? 'selected' : '' }}>
                                    {{ $buyer->name }} ({{ $buyer->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('buyer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($buyers->count() == 0)
                            <div class="alert alert-warning mt-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>No buyers available!</strong> You need to add buyers to the system before creating transactions.
                                <a href="{{ route('buyers.create') }}" class="btn btn-sm btn-primary ms-2">
                                    <i class="fas fa-plus me-1"></i>Add First Buyer
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="row" id="buyer-info" style="display: none;">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <div class="form-control-plaintext" id="buyer-email"></div>
                            </div>
                        </div>
                    </div>


                    <div class="mb-3">
                        <label for="product_id" class="form-label">Product <span class="text-danger">*</span></label>
                        <select class="form-select @error('product_id') is-invalid @enderror" 
                                id="product_id" name="product_id" required>
                            <option value="">Select a product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                        data-price="{{ $product->price }}"
                                        data-stock="{{ $product->quantity }}"
                                        {{ (old('product_id') == $product->id || $selectedProductId == $product->id) ? 'selected' : '' }}>
                                    {{ $product->name }} - {{ $product->category->name }} 
                                    (Stock: {{ $product->quantity }}, Price: ₱{{ number_format($product->price, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($products->count() == 0)
                            <div class="form-text">
                                <a href="{{ route('products.create') }}" class="text-decoration-none">
                                    <i class="fas fa-plus me-1"></i>Add products first
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity_purchased" class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('quantity_purchased') is-invalid @enderror" 
                                       id="quantity_purchased" name="quantity_purchased" value="{{ old('quantity_purchased') }}" 
                                       min="1" required>
                                @error('quantity_purchased')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" id="stock-info"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Total Amount</label>
                                <div class="form-control-plaintext" id="total-amount">
                                    ₱0.00
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Transactions
                        </a>
                        <button type="submit" class="btn btn-primary" {{ $buyers->count() == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-save me-1"></i>Create Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buyerSelect = document.getElementById('buyer_id');
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity_purchased');
    const totalAmount = document.getElementById('total-amount');
    const stockInfo = document.getElementById('stock-info');
    const buyerInfo = document.getElementById('buyer-info');
    const buyerEmail = document.getElementById('buyer-email');

    function updateBuyerInfo() {
        const selectedOption = buyerSelect.options[buyerSelect.selectedIndex];
        
        if (selectedOption.value) {
            buyerEmail.textContent = selectedOption.dataset.email;
            buyerInfo.style.display = 'block';
        } else {
            buyerInfo.style.display = 'none';
        }
    }

    function updateTotal() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = parseFloat(selectedOption.dataset.price) || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const total = price * quantity;
        
        totalAmount.textContent = '₱' + total.toFixed(2);
    }

    function updateStockInfo() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const stock = parseInt(selectedOption.dataset.stock) || 0;
        
        if (selectedOption.value) {
            stockInfo.textContent = `Available stock: ${stock}`;
            quantityInput.max = stock;
        } else {
            stockInfo.textContent = '';
        }
    }

    buyerSelect.addEventListener('change', function() {
        updateBuyerInfo();
    });

    productSelect.addEventListener('change', function() {
        updateStockInfo();
        updateTotal();
    });

    quantityInput.addEventListener('input', function() {
        updateTotal();
    });

    // Initial update
    updateBuyerInfo();
    updateStockInfo();
    updateTotal();
});
</script>
@endsection
