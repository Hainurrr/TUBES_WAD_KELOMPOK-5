<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {


        // Force HTTPS hanya di production (Heroku), bukan di local development
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        // Bagikan notifikasi (database notifications) ke navbar
        view()->composer('layouts.navbar', function ($view) {
            $notifications = [];
            if (auth()->check()) {
                $notifications = auth()->user()->unreadNotifications;
            }
            $view->with('notifications', $notifications);
        });
    }
}
