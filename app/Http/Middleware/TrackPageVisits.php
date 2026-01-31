<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SiteVisit;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class TrackPageVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip if admin, api, or excluded paths
        if ($request->is('admin*', 'api*', 'livewire*', '_debugbar*', 'sanctum*')) {
            return $next($request);
        }

        // Simple bot detection (very basic)
        $userAgent = $request->userAgent();
        if (Str::contains(strtolower($userAgent), ['bot', 'crawler', 'spider', 'uptime'])) {
            return $next($request);
        }

        // Get or Create Visitor ID
        $visitorId = $request->cookie('visitor_id');
        if (!$visitorId) {
            $visitorId = (string) Str::uuid();
            Cookie::queue('visitor_id', $visitorId, 60 * 24 * 365); // 1 year
        }

        try {
            SiteVisit::create([
                'ip_address' => $request->ip(),
                'url' => $request->fullUrl(),
                'user_agent' => substr($userAgent, 0, 255),
                'referer' => substr($request->header('referer'), 0, 255),
                'visitor_id' => $visitorId,
            ]);
        } catch (\Exception $e) {
            // Silently fail logging to not disrupt user
        }

        return $next($request);
    }
}
