<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin;
use App\Http\Controllers\Api\V1\Customer;

// Endpoint untuk Pelanggan (Customer)
Route::prefix('v1/customer')->name('customer.v1.')->group(function () {
    Route::apiResource('products', Customer\ProductController::class)->only(['index', 'show']);
});


// Endpoint untuk Administrator (Admin)
Route::prefix('v1/admin')->name('admin.v1.')->group(function () {
    Route::apiResource('product-categories', Admin\ProductCategoryController::class);
    Route::apiResource('products', Admin\ProductController::class);
});