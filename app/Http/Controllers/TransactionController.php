<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Buyer;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statusFilter = $request->get('status');
        $productFilter = $request->get('product');
        $searchQuery = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $transactionsQuery = Transaction::with(['product.category', 'buyer']);

        // Apply status filter
        if ($statusFilter && $statusFilter !== 'all') {
            $transactionsQuery->where('status', $statusFilter);
        }

        // Apply product filter
        if ($productFilter && $productFilter !== 'all') {
            $transactionsQuery->where('product_id', $productFilter);
        }

        // Apply search filter
        if ($searchQuery) {
            $transactionsQuery->where(function($query) use ($searchQuery) {
                $query->where('buyer_name', 'like', '%' . $searchQuery . '%')
                      ->orWhere('buyer_email', 'like', '%' . $searchQuery . '%')
                      ->orWhereHas('buyer', function($q) use ($searchQuery) {
                          $q->where('name', 'like', '%' . $searchQuery . '%')
                            ->orWhere('email', 'like', '%' . $searchQuery . '%');
                      })
                      ->orWhereHas('product', function($q) use ($searchQuery) {
                          $q->where('name', 'like', '%' . $searchQuery . '%');
                      });
            });
        }

        // Apply date filters
        if ($dateFrom) {
            $transactionsQuery->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $transactionsQuery->whereDate('created_at', '<=', $dateTo);
        }

        $transactions = $transactionsQuery->orderBy('created_at', 'desc')->paginate(10);
        
        // Calculate financial statistics
        $stats = [
            'total_inventory_value' => \App\Models\Product::sum(\DB::raw('quantity * price')),
            'total_sales_value' => Transaction::where('status', 'completed')->sum('total_amount'),
        ];

        // Get products for filter dropdown
        $products = \App\Models\Product::orderBy('name')->get();
        
        return view('transactions.index', compact(
            'transactions', 
            'stats', 
            'products',
            'statusFilter',
            'productFilter',
            'searchQuery',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $products = Product::where('quantity', '>', 0)->with('category')->get();
        $buyers = Buyer::orderBy('name')->get();
        $selectedProductId = $request->get('product_id');
        $selectedBuyerId = $request->get('buyer_id');
        
        return view('transactions.create', compact('products', 'buyers', 'selectedProductId', 'selectedBuyerId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'buyer_id' => 'required|exists:buyers,id',
            'product_id' => 'required|exists:products,id',
            'quantity_purchased' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $buyer = Buyer::findOrFail($request->buyer_id);

        // Check if product has enough stock
        if (!$product->hasStock($request->quantity_purchased)) {
            return back()->withErrors(['quantity_purchased' => 'Insufficient stock. Available: ' . $product->quantity])->withInput();
        }

        $unitPrice = $product->price;
        $totalAmount = $unitPrice * $request->quantity_purchased;

        $transaction = Transaction::create([
            'buyer_id' => $request->buyer_id,
            'buyer_name' => $buyer->name,
            'buyer_email' => $buyer->email,
            'buyer_phone' => $buyer->phone,
            'product_id' => $request->product_id,
            'quantity_purchased' => $request->quantity_purchased,
            'unit_price' => $unitPrice,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction created successfully. Please complete the purchase to update stock.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['product.category', 'buyer']);
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Complete a transaction and update stock.
     */
    public function complete(Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Only pending transactions can be completed.');
        }

        $product = $transaction->product;
        
        if (!$product->hasStock($transaction->quantity_purchased)) {
            return back()->with('error', 'Insufficient stock to complete this transaction.');
        }

        // Update stock and transaction status
        $product->reduceStock($transaction->quantity_purchased);
        $transaction->update(['status' => 'completed']);

        return back()->with('success', 'Transaction completed successfully. Stock updated.');
    }

    /**
     * Cancel a transaction.
     */
    public function cancel(Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Only pending transactions can be cancelled.');
        }

        $transaction->update(['status' => 'cancelled']);

        return back()->with('success', 'Transaction cancelled successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        if ($transaction->status === 'completed') {
            return back()->with('error', 'Cannot delete completed transactions.');
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
}
