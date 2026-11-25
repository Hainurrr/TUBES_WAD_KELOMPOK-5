<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->tenant) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda tidak terhubung dengan gerai tenant.',
                ], 403);
            }

            return redirect()->route('home')->with('error', 'Akun Anda tidak terhubung dengan Tenant manapun.');
        }

        return $next($request);
    }
}
