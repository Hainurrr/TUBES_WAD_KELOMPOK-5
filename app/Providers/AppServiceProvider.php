<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        view()->composer('layouts.navbar', function ($view) {
            $notifications = auth()->check()
                ? auth()->user()->unreadNotifications
                : [];

            $view->with('notifications', $notifications);
        });
    }
}
