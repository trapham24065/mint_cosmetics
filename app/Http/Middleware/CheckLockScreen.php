<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckLockScreen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin is authenticated
        if (Auth::guard('web')->check()) {
            // Check if screen is locked
            if (session('admin_locked') === true) {
                // Allow access to lock screen and unlock routes
                if (!$request->routeIs('admin.lock') && !$request->routeIs('admin.unlock')) {
                    return redirect()->route('admin.lock');
                }
            }
        }

        return $next($request);
    }
}
