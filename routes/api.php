<?php

// MANPROSI_TELYUCAN_IMPROVEMENT - API Routes untuk Midtrans Webhook
// Route di file ini otomatis memiliki prefix '/api' dan tidak memerlukan CSRF token.

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes - Midtrans Webhook
|--------------------------------------------------------------------------
| Endpoint ini menerima HTTP POST Notification dari server Midtrans.
| URL yang harus didaftarkan di Dashboard Midtrans:
| https://your-ngrok-url.ngrok.io/api/midtrans/notification
|
| Untuk testing lokal:
| 1. Jalankan: ngrok http 8000
| 2. Salin URL HTTPS dari ngrok
| 3. Masuk ke Dashboard Midtrans > Settings > Configuration > Notification URL
| 4. Paste URL: https://xxxx.ngrok.io/api/midtrans/notification
*/

Route::post('/midtrans/notification', [PaymentController::class, 'handleNotification']);
