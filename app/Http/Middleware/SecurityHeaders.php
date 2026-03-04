<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // === Clickjacking Protection ===
        $response->headers->set('X-Frame-Options', 'DENY');

        // === MIME-type Sniffing Prevention ===
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // === XSS Protection (legacy browsers) ===
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // === Referrer Policy ===
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // === Permissions Policy ===
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=()');

        // === Cross-Domain Policies ===
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // === HSTS - Force HTTPS (1 year) ===
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // === Cross-Origin Policies ===
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Embedder-Policy', 'unsafe-none'); // 'require-corp' breaks external fonts/scripts

        // === Hide Server Info ===
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');

        // === Content Security Policy ===
        $csp = implode(' ', [
            "default-src 'self';",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://unpkg.com https://cdn.jsdelivr.net;",
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://fonts.googleapis.com https://unpkg.com;",
            "font-src 'self' https://fonts.bunny.net https://fonts.gstatic.com data:;",
            "img-src 'self' data: https:;",
            "connect-src 'self' https://fonts.bunny.net;",
            "frame-ancestors 'none';",
            "base-uri 'self';",
            "form-action 'self';",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
