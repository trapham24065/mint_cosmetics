<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TrackVisits
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('get') && !$request->ajax()) {
            $ipAddress = $request->ip();
            $today = Carbon::today();

            $hasVisitedToday = Visit::where('ip_address', $ipAddress)
                ->whereDate('visited_at', $today)
                ->exists();

            if (!$hasVisitedToday) {
                try {
                    Visit::create([
                        'ip_address' => $ipAddress,
                        'user_agent' => $request->userAgent(),
                        'visited_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    Log::error("Failed to track visit: ".$e->getMessage());
                }
            }
        }

        return $next($request);
    }

}
