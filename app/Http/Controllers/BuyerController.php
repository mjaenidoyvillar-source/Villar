<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buyers = Buyer::withCount('transactions')
            ->withSum('transactions', 'total_amount')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('buyers.index', compact('buyers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('buyers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:buyers,email',
        ]);

        Buyer::create($request->all());

        return redirect()->route('buyers.index')
            ->with('success', 'Buyer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Buyer $buyer)
    {
        $buyer->load(['transactions.product']);
        
        return view('buyers.show', compact('buyer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buyer $buyer)
    {
        return view('buyers.edit', compact('buyer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Buyer $buyer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:buyers,email,' . $buyer->id,
        ]);

        $buyer->update($request->all());

        return redirect()->route('buyers.index')
            ->with('success', 'Buyer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Buyer $buyer)
    {
        // Check if buyer has transactions
        if ($buyer->transactions()->count() > 0) {
            return redirect()->route('buyers.index')
                ->with('error', 'Cannot delete buyer with existing transactions.');
        }

        $buyer->delete();

        return redirect()->route('buyers.index')
            ->with('success', 'Buyer deleted successfully.');
    }
}
