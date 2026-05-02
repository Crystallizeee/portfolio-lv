<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorAuthenticated
{
    /**
     * Handle an incoming request.
     * If the authenticated user has 2FA enabled but hasn't verified
     * it in this session yet, redirect them to the challenge page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            // Allow the two-factor challenge page always
            if ($request->routeIs('admin.two-factor')) {
                return $next($request);
            }

            if ($user->hasTwoFactorEnabled()) {
                // 2FA is configured — ensure it has been verified in this session
                if (! $request->session()->get('two_factor_verified')) {
                    return redirect()->route('admin.two-factor');
                }
            } else {
                // 2FA is NOT yet configured — MANDATORY: force user to set it up.
                // Allow access to profile settings so they can enable it.
                if (! $request->routeIs('admin.profile')) {
                    return redirect()->route('admin.profile')
                        ->with('two_factor_required', true);
                }
            }
        }

        return $next($request);
    }
}
