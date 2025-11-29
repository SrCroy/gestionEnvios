<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Forzar la URL base para subdirectorios
        if (env('APP_ENV') !== 'production') {
            URL::forceRootUrl(env('APP_URL'));
        }
        
        // Si usas HTTPS en producción
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}