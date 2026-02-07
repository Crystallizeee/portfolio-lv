<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Content Security Policy
        // Allowing 'unsafe-inline' and 'unsafe-eval' for Livewire/Alpine compliance for now.
        // Allowing unpkg, cdn.jsdelivr, fonts.bunny for external assets.
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://unpkg.com https://cdn.jsdelivr.net; " .
               "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://unpkg.com; " .
               "font-src 'self' https://fonts.bunny.net data:; " .
               "img-src 'self' data: https:; " .
               "connect-src 'self' https://fonts.bunny.net;";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
