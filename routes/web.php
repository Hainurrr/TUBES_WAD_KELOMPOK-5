<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Tenant\MenuController;

Route::get('/menus', [OrderController::class, 'catalog'])->name('menus.index');
Route::get('/', [OrderController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::post('/cart/add/{id}', [OrderController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [OrderController::class, 'updateCart'])->name('cart.update');
    Route::get('/cart', [OrderController::class, 'cart'])->name('cart.index');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout/store', [OrderController::class, 'storeOrder'])->name('order.store');
});
Route::get('/tenant/{id}', [OrderController::class, 'showTenant'])->name('tenant.show')->where('id', '[0-9]+');

Route::get('/search', [OrderController::class, 'search'])->name('search');

// Auth Routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('tenants', TenantController::class);
    
    // User Management
    Route::resource('users', \App\Http\Controllers\AdminUserController::class);
});

// Tenant Routes
Route::middleware(['auth', 'role:tenant'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Tenant\TenantOrderController::class, 'index'])->name('dashboard');
    Route::resource('menus', MenuController::class);

    // Order Management Routes
    Route::get('orders/history', [\App\Http\Controllers\Tenant\TenantOrderController::class, 'history'])->name('orders.history');
    Route::post('orders/{id}/update-status', [\App\Http\Controllers\Tenant\TenantOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('update-status', [\App\Http\Controllers\Tenant\TenantOrderController::class, 'updateTenantStatus'])->name('updateStatus');
    Route::get('orders', [\App\Http\Controllers\Tenant\TenantOrderController::class, 'index'])->name('orders.index');

    // Review Management Routes
    Route::get('reviews', [\App\Http\Controllers\ReviewController::class, 'indexTenant'])->name('reviews.index');
});

// Customer Routes (Authenticated)
Route::middleware(['auth'])->group(function () {
    Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{id}', [\App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    Route::get('/history', [\App\Http\Controllers\OrderController::class, 'history'])->name('orders.history');
    Route::post('/orders/{id}/cancel', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
});
