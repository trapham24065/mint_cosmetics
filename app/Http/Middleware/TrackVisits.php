<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Visit;

class TrackVisits
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Chỉ đếm các request GET (xem trang), bỏ qua API hoặc AJAX nếu muốn
        if ($request->isMethod('get') && !$request->ajax()) {
            Visit::create([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'visited_at' => now(),
            ]);
        }

        return $next($request);
    }

}
