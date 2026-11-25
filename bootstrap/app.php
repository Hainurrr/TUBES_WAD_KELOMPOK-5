<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureUserHasTenant;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->append(SecurityHeaders::class);
        $middleware->alias([
            'role' => CheckRole::class,
            'has.tenant' => EnsureUserHasTenant::class,
        ]);
        $middleware->redirectTo(
            guests: '/login',
            users: function ($request) {
                $user = $request->user();
                return $user ? route($user->dashboardRouteName()) : '/';
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
    })->create();
