<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login to access the admin dashboard.');
        }

        if (!Auth::user()->is_admin) {
            return redirect('/')->with('error', 'Access denied. You must be an administrator to access the admin dashboard.');
        }

        return $next($request);
    }
}
