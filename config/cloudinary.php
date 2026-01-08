<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Cloudinary
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat mengkonfigurasi pengaturan Cloudinary. Cloudinary adalah
    | layanan cloud yang menawarkan solusi untuk seluruh pipeline pengelolaan
    | gambar pada aplikasi web.
    |
    */

    'cloud_url' => env('CLOUDINARY_URL'),

    /**
     * Preset Upload (Opsional)
     */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    /**
     * URL Notifikasi (Opsional)
     */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),

];
