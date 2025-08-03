<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin;
use App\Http\Controllers\Api\V1\Customer;
use App\Http\Controllers\Api\V1\AuthController;

// Rute Publik (Autentikasi & Produk untuk Customer)
Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::prefix('customer')->name('customer.v1.')->group(function () {
        Route::apiResource('products', Customer\ProductController::class)->only(['index', 'show']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// Endpoint untuk Administrator (Admin)
Route::prefix('v1/admin')
    ->middleware(['auth:sanctum', 'role:admin'])
    ->name('admin.v1.')
    ->group(function () {
        Route::apiResource('product-categories', Admin\ProductCategoryController::class);
        Route::apiResource('products', Admin\ProductController::class);
        Route::apiResource('products.images', Admin\ProductImageController::class)
            ->only(['store', 'destroy'])
            ->shallow();
        Route::apiResource('add-ons', Admin\AddOnController::class);
        Route::apiResource('attributes', Admin\AttributeController::class);
        Route::apiResource('attributes.values', Admin\AttributeValueController::class)->shallow();
        Route::apiResource('products.options', Admin\ProductOptionController::class)->shallow();
        Route::post('products/{product}/add-ons', [Admin\ProductAddOnController::class, 'store'])->name('products.addons.store');
        Route::delete('products/{product}/add-ons/{add_on}', [Admin\ProductAddOnController::class, 'destroy'])->name('products.addons.destroy');
    });
