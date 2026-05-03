<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS if enabled via .env (set FORCE_HTTPS=true on production)
        if (env('FORCE_HTTPS', false)) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Configure trusted proxies (for Nginx/Cloudflare reverse proxy)
        if ($proxies = env('TRUSTED_PROXIES')) {
            \Illuminate\Http\Request::setTrustedProxies(
                $proxies === '*' ? ['127.0.0.1', '::1'] : explode(',', $proxies),
                \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO
            );
        }

        try {
            // Share global SEO data with all views
            $seo = \App\Models\SeoMetadata::where('model_type', 'global')->first();
            \Illuminate\Support\Facades\View::share('seo', $seo);
        } catch (\Exception $e) {
            // Quietly fail if table doesn't exist yet
        }
    }
}
