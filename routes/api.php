<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin;
use App\Http\Controllers\Api\V1\Customer;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CartItemController;

// Rute Publik (Autentikasi & Produk untuk Customer)
Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth.optional')->group(function () {
        Route::get('/cart', [CartController::class, 'show']);
        Route::delete('/cart', [CartController::class, 'clear']);
        Route::post('/cart/items', [CartItemController::class, 'store']);
        Route::patch('/cart/items/{cartItem}', [CartItemController::class, 'update']);
        Route::delete('/cart/items/{cartItem}', [CartItemController::class, 'destroy']);
    });

    Route::prefix('customer')->name('customer.v1.')->group(function () {
        Route::apiResource('products', Customer\ProductController::class)->only(['index', 'show']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/checkout', [\App\Http\Controllers\Api\V1\CheckoutController::class, 'store']);
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
