<?php

// MANPROSI_TELYUCAN_IMPROVEMENT - Konfigurasi Midtrans Payment Gateway
// File ini membaca nilai dari .env untuk koneksi ke Midtrans API

return [

    /*
    |--------------------------------------------------------------------------
    | Midtrans Server Key
    |--------------------------------------------------------------------------
    | Server Key digunakan untuk autentikasi server-to-server dengan Midtrans.
    | Dapatkan dari Dashboard Midtrans > Settings > Access Keys.
    */
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Midtrans Client Key
    |--------------------------------------------------------------------------
    | Client Key digunakan di frontend untuk inisialisasi Snap.js popup.
    | Dapatkan dari Dashboard Midtrans > Settings > Access Keys.
    */
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Production Mode
    |--------------------------------------------------------------------------
    | Set ke true jika sudah siap untuk transaksi nyata (production).
    | Default: false (sandbox/testing mode).
    */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    /*
    |--------------------------------------------------------------------------
    | Sanitize Input
    |--------------------------------------------------------------------------
    | Jika true, Midtrans akan membersihkan input dari karakter berbahaya.
    */
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),

    /*
    |--------------------------------------------------------------------------
    | 3D Secure
    |--------------------------------------------------------------------------
    | Aktifkan verifikasi 3D Secure untuk pembayaran kartu kredit.
    */
    'is_3ds' => env('MIDTRANS_IS_3DS', true),

];
