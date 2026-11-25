<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Tenant\MenuController;
use App\Http\Controllers\Tenant\TenantOrderController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

// Public Storefront & Discovery Routes
Route::get('/', [OrderController::class, 'index'])->name('home');
Route::get('/menus', [OrderController::class, 'catalog'])->name('menus.index');
Route::get('/search', [OrderController::class, 'search'])->name('search');
Route::get('/tenant/{id}', [OrderController::class, 'showTenant'])
    ->whereNumber('id')
    ->name('tenant.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Shared Authenticated Account Routes (All Roles)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/notifications/read/{id}', [OrderController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/read-all', [OrderController::class, 'markAllRead'])->name('notifications.readAll');
});

// Ordering, Payment & Review Routes (Customer & Tenant)
Route::middleware(['auth', 'role:customer,tenant'])->group(function () {
    Route::post('/cart/add/{id}', [OrderController::class, 'addToCart'])
        ->whereNumber('id')
        ->name('cart.add');
    Route::post('/cart/update', [OrderController::class, 'updateCart'])->name('cart.update');
    Route::get('/cart', [OrderController::class, 'cart'])->name('cart.index');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout/store', [PaymentController::class, 'checkout'])
        ->middleware('throttle:20,1')
        ->name('order.store');
    Route::get('/payment/{transactionGroup}', [PaymentController::class, 'paymentPage'])->name('payment.page');

    Route::post('/reviews', [ReviewController::class, 'store'])
        ->middleware('throttle:20,1')
        ->name('reviews.store');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])
        ->whereNumber('id')
        ->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])
        ->whereNumber('id')
        ->name('reviews.destroy');

    Route::get('/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{id}/print', [OrderController::class, 'print'])
        ->whereNumber('id')
        ->name('orders.print');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])
        ->whereNumber('id')
        ->name('orders.cancel');
});

// Admin Panel Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('tenants/export-pdf', [TenantController::class, 'exportPdf'])->name('tenants.pdf');
    Route::resource('tenants', TenantController::class)->except(['show']);
    Route::resource('users', AdminUserController::class)->except(['show']);

    Route::get('reviews', [ReviewController::class, 'indexAdmin'])->name('reviews.index');
    Route::delete('reviews/{id}', [ReviewController::class, 'destroyAdmin'])
        ->whereNumber('id')
        ->name('reviews.destroy');
    Route::get('reviews/export', [ReviewController::class, 'exportAdmin'])->name('reviews.export');
});

// Tenant Panel Routes
Route::middleware(['auth', 'role:tenant', 'has.tenant'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('dashboard', [TenantOrderController::class, 'index'])->name('dashboard');
    Route::get('menus/export-pdf', [MenuController::class, 'exportPdf'])->name('menus.pdf');
    Route::resource('menus', MenuController::class)->except(['show']);

    Route::get('orders', [TenantOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/history', [TenantOrderController::class, 'history'])->name('orders.history');
    Route::get('orders/export-pdf', [TenantOrderController::class, 'exportPdf'])->name('orders.pdf');
    Route::post('orders/{id}/update-status', [TenantOrderController::class, 'updateStatus'])
        ->whereNumber('id')
        ->name('orders.updateStatus');
    Route::post('orders/store-manual', [TenantOrderController::class, 'storeManualHistory'])->name('orders.storeManual');
    Route::delete('orders/{id}', [TenantOrderController::class, 'destroy'])
        ->whereNumber('id')
        ->name('orders.destroy');
    Route::post('update-status', [TenantOrderController::class, 'updateTenantStatus'])->name('updateStatus');

    Route::get('reviews', [ReviewController::class, 'indexTenant'])->name('reviews.index');
});
