<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Webhook notifikasi pembayaran (verifikasi signature SHA512 di OrderCheckoutService)
Route::post('/midtrans/notification', [PaymentController::class, 'handleNotification'])
    ->middleware('throttle:60,1');
