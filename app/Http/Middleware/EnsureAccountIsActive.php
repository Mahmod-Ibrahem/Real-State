<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->is_active !== false) {
            return $next($request);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $message = __('Your account is disabled.');

        if ($request->expectsJson()) {
            return response()->json(['message' => $message], Response::HTTP_UNAUTHORIZED);
        }

        $login = $request->is('supplier', 'supplier/*') ? 'supplier.login' : 'login';

        return to_route($login)->withErrors(['email' => $message]);
    }
}
