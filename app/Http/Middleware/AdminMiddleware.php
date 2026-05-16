<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminMiddleware
 * Restricts access to admin-only routes.
 * Redirects non-admins to the user dashboard with an error message.
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in AND has admin role
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Access denied. Administrator privileges required.');
        }

        return $next($request);
    }
}
