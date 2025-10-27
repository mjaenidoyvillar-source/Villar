<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BuyerController;

Route::get('/', function () {
    return redirect()->route('products.index');
});
Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);
Route::resource('transactions', TransactionController::class);
Route::resource('buyers', BuyerController::class);
Route::post('transactions/{transaction}/complete', [TransactionController::class, 'complete'])->name('transactions.complete');
Route::post('transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');
