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
        // Pastikan ada titik koma (;) di akhir baris dan kurung kurawal yang benar
        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
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
