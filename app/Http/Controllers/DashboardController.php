<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Calculate dashboard statistics
        $stats = $this->getDashboardStats();

        return view('dashboard.index', compact('stats'));
    }

    private function getDashboardStats()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalTransactions = Transaction::count();
        $completedTransactions = Transaction::where('status', 'completed')->count();
        $pendingTransactions = Transaction::where('status', 'pending')->count();

        // Stock statistics
        $inStockProducts = Product::where('quantity', '>', 10)->count();
        $lowStockProducts = Product::where('quantity', '>', 0)->where('quantity', '<=', 10)->count();
        $outOfStockProducts = Product::where('quantity', 0)->count();

        // Calculate total inventory value
        $totalInventoryValue = Product::sum(\DB::raw('quantity * price'));

        // Calculate total sales value
        $totalSalesValue = Transaction::where('status', 'completed')->sum('total_amount');

        return [
            'total_products' => $totalProducts,
            'total_categories' => $totalCategories,
            'total_transactions' => $totalTransactions,
            'completed_transactions' => $completedTransactions,
            'pending_transactions' => $pendingTransactions,
            'in_stock_products' => $inStockProducts,
            'low_stock_products' => $lowStockProducts,
            'out_of_stock_products' => $outOfStockProducts,
            'total_inventory_value' => $totalInventoryValue,
            'total_sales_value' => $totalSalesValue,
        ];
    }
}
