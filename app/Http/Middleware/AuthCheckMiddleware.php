<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the current route matches '/admin/donations'
        if ($request->getRequestUri() === '/admin/donations' || $request->getRequestUri() === '/admin') {
            // Check if the user is authenticated
            if (auth()->check()) {
                return $next($request); // Allow access if authenticated
            }

            // Redirect unauthenticated users to the 'access-blocked' route
            return redirect()->back();
        }

        // Allow access to all other routes
        return $next($request);
    }

}
