<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckActiveUser Middleware
 * Ensures the authenticated user account is still active.
 * Admins can deactivate accounts, and this middleware enforces that.
 */
class CheckActiveUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Your account has been deactivated. Please contact the administrator.');
        }

        return $next($request);
    }
}
