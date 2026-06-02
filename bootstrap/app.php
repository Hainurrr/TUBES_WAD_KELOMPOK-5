<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // MANPROSI_TELYUCAN_IMPROVEMENT - Webhook Midtrans
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
        $middleware->redirectTo(
            guests: '/login',
            users: function ($request) {
                if (!$request->user()) return '/';
                return match ($request->user()->role) {
                    'admin' => route('admin.dashboard'),
                    'tenant' => route('tenant.dashboard'),
                    default => route('home'),
                };
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
