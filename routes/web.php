<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\Tenant\MenuController;

Route::get('/menus', [OrderController::class, 'catalog'])->name('menus.index');
Route::get('/', [OrderController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::post('/cart/add/{id}', [OrderController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [OrderController::class, 'updateCart'])->name('cart.update');
    Route::get('/cart', [OrderController::class, 'cart'])->name('cart.index');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout/store', [OrderController::class, 'storeOrder'])->name('order.store');

    // Profile Routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
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
use App\Http\Controllers\TenantController;
// Gunakan AdminController untuk Dashboard
use App\Http\Controllers\AdminController; 

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard'); 
    Route::get('tenants/export-pdf', [TenantController::class, 'exportPdf'])->name('tenants.pdf');
    Route::resource('tenants', TenantController::class);
    
    // User Management
    Route::resource('users', \App\Http\Controllers\AdminUserController::class);

    // Review Management
    Route::get('reviews', [\App\Http\Controllers\ReviewController::class, 'indexAdmin'])->name('reviews.index');
    Route::delete('reviews/{id}', [\App\Http\Controllers\ReviewController::class, 'destroyAdmin'])->name('reviews.destroy');
    Route::get('reviews/export', [\App\Http\Controllers\ReviewController::class, 'exportAdmin'])->name('reviews.export');
});

// Tenant Routes
Route::middleware(['auth', 'role:tenant'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Tenant\TenantOrderController::class, 'index'])->name('dashboard'); // Alias dashboard
    Route::get('menus/export-pdf', [MenuController::class, 'exportPdf'])->name('menus.pdf');
    Route::resource('menus', MenuController::class);

    // Order Management Routes
    Route::get('orders/history', [\App\Http\Controllers\Tenant\TenantOrderController::class, 'history'])->name('orders.history');
    Route::get('orders/export-pdf', [\App\Http\Controllers\Tenant\TenantOrderController::class, 'exportPdf'])->name('orders.pdf');
    Route::post('orders/{id}/update-status', [\App\Http\Controllers\Tenant\TenantOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('orders/store-manual', [\App\Http\Controllers\Tenant\TenantOrderController::class, 'storeManualHistory'])->name('orders.storeManual'); // Route Manual History
    Route::delete('orders/{id}', [\App\Http\Controllers\Tenant\TenantOrderController::class, 'destroy'])->name('orders.destroy'); // Route Delete
    Route::post('update-status', [\App\Http\Controllers\Tenant\TenantOrderController::class, 'updateTenantStatus'])->name('updateStatus'); // Route baru
    Route::get('orders', [\App\Http\Controllers\Tenant\TenantOrderController::class, 'index'])->name('orders.index');

    // Review Management Routes
    Route::get('reviews', [\App\Http\Controllers\ReviewController::class, 'indexTenant'])->name('reviews.index');
    // Export dihapus untuk Tenant
});

// Customer Routes (Authenticated)
Route::middleware(['auth'])->group(function () {
    Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{id}', [\App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    Route::get('/history', [\App\Http\Controllers\OrderController::class, 'history'])->name('orders.history');
    Route::get('/notifications/read/{id}', [\App\Http\Controllers\OrderController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/read-all', [\App\Http\Controllers\OrderController::class, 'markAllRead'])->name('notifications.readAll');
    Route::get('/orders/{id}/print', [\App\Http\Controllers\OrderController::class, 'print'])->name('orders.print');
    Route::post('/orders/{id}/cancel', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
});
