<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = auth()->user();

        switch ($role) {
            case 'admin':
                if (!$user->isAdmin()) {
                    abort(403, 'Unauthorized access. Admin privileges required.');
                }
                break;
            case 'author':
                if (!$user->isAdmin() && !$user->isAuthor()) {
                    abort(403, 'Unauthorized access. Author or Admin privileges required.');
                }
                break;
            case 'user':
                // All authenticated users can access user routes
                break;
            default:
                abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
