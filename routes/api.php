<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin;
use App\Http\Controllers\Api\V1\Customer;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CartItemController;
use App\Http\Controllers\Api\V1\CheckoutController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\OrderController;

// Semua rute API berada di dalam prefix v1
Route::prefix('v1')->group(function () {

    // --- Rute Publik (Tidak Perlu Login) ---
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/guest-checkout', [CheckoutController::class, 'store']);

    Route::prefix('customer')->name('customer.v1.')->group(function () {
        Route::apiResource('products', Customer\ProductController::class)->only(['index', 'show']);
        Route::apiResource('product-categories', Customer\ProductCategoryController::class)->only(['index', 'show']);
        Route::apiResource('gallery-items', Customer\GalleryItemController::class)->only(['index', 'show']);
    });

    // --- Rute dengan Autentikasi Opsional (Untuk Keranjang Tamu) ---
    Route::middleware('auth.optional')->group(function () {
        Route::get('/cart', [CartController::class, 'show']);
        Route::delete('/cart', [CartController::class, 'clear']);
        Route::post('/cart/items', [CartItemController::class, 'store']);
        Route::patch('/cart/items/{cartItem}', [CartItemController::class, 'update']);
        Route::delete('/cart/items/{cartItem}', [CartItemController::class, 'destroy']);
    });

    // --- Rute Terproteksi (Wajib Login) ---
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/checkout', [CheckoutController::class, 'store']);

        // Rute untuk mengelola profil pengguna
        Route::get('/user', [ProfileController::class, 'show']);
        Route::put('/user', [ProfileController::class, 'update']);
        Route::post('/user/change-password', [ProfileController::class, 'changePassword']);

        // Rute untuk mengelola pesanan pengguna
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
    });
});

// --- Endpoint untuk Administrator (Admin) ---
Route::prefix('v1/admin')
    ->middleware(['auth:sanctum', 'role:admin'])
    ->name('admin.v1.')
    ->group(function () {
        Route::apiResource('users', Admin\UserController::class);
        Route::apiResource('product-categories', Admin\ProductCategoryController::class);
        Route::apiResource('products', Admin\ProductController::class);
        Route::post('variants/{variant}/images', [Admin\ProductImageController::class, 'store'])->name('variants.images.store');
        Route::delete('images/{image}', [Admin\ProductImageController::class, 'destroy'])->name('images.destroy');
        Route::apiResource('add-ons', Admin\AddOnController::class);
        Route::apiResource('attributes', Admin\AttributeController::class);
        Route::apiResource('attributes.values', Admin\AttributeValueController::class)->shallow();
        Route::apiResource('products.variants', Admin\ProductVariantController::class)->shallow();
        Route::post('products/{product}/add-ons', [Admin\ProductAddOnController::class, 'store'])->name('products.addons.store');
        Route::delete('products/{product}/add-ons/{add_on}', [Admin\ProductAddOnController::class, 'destroy'])->name('products.addons.destroy');
        Route::apiResource('gallery-items', Admin\GalleryItemController::class);
        Route::apiResource('orders', Admin\OrderController::class);
    });
