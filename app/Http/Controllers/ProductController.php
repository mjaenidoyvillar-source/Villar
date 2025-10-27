<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $categoryFilter = $request->get('category');
        $stockFilter = $request->get('stock');
        $searchQuery = $request->get('search');
        $recentFilter = $request->get('recent');

        // Build query for products
        $productsQuery = Product::with('category');

        // Apply category filter
        if ($categoryFilter && $categoryFilter !== 'all') {
            $productsQuery->where('category_id', $categoryFilter);
        }

        // Apply stock filter
        if ($stockFilter) {
            switch ($stockFilter) {
                case 'in_stock':
                    $productsQuery->where('quantity', '>', 10);
                    break;
                case 'low_stock':
                    $productsQuery->where('quantity', '>', 0)->where('quantity', '<=', 10);
                    break;
                case 'out_of_stock':
                    $productsQuery->where('quantity', 0);
                    break;
            }
        }

        // Apply search filter
        if ($searchQuery) {
            $productsQuery->where('name', 'like', '%' . $searchQuery . '%');
        }

        // Apply recent filter
        if ($recentFilter) {
            switch ($recentFilter) {
                case 'today':
                    $productsQuery->whereDate('created_at', today());
                    break;
                case 'week':
                    $productsQuery->where('created_at', '>=', now()->subWeek());
                    break;
                case 'month':
                    $productsQuery->where('created_at', '>=', now()->subMonth());
                    break;
            }
        }

        $products = $productsQuery->orderBy('name')->paginate(10);

        // Get all categories for filter dropdown
        $categories = Category::orderBy('name')->get();

        // Calculate product statistics
        $stats = [
            'total_products' => Product::count(),
            'in_stock_products' => Product::where('quantity', '>', 10)->count(),
            'low_stock_products' => Product::where('quantity', '>', 0)->where('quantity', '<=', 10)->count(),
            'out_of_stock_products' => Product::where('quantity', 0)->count(),
        ];

        return view('products.index', compact(
            'products',
            'categories',
            'categoryFilter',
            'stockFilter',
            'searchQuery',
            'recentFilter',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category');
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
