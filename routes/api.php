<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartsController;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('customers', CustomerController::class);

Route::prefix('cart')->group(function () {
    Route::post('add-item', [CartsController::class, 'addProductToCart']);
    Route::delete('delete-item/{cartItemId}', [CartsController::class, 'deleteCartItem']);
    Route::get('items/{customerId}', [CartsController::class, 'getCartItems']);
    Route::post('/checkout', [CartsController::class, 'checkout']);
});