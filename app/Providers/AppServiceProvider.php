<?php

namespace App\Providers;

use App\Support\SiteSettings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Ajustes del sitio: una sola instancia (y una sola consulta) por petición.
        $this->app->singleton(SiteSettings::class, fn () => new SiteSettings());
    }

    public function boot(): void
    {
        // Forzar HTTPS en producción (detrás de proxy/hosting).
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Exponer los ajustes a todas las vistas como $ajustes.
        View::share('ajustes', $this->app->make(SiteSettings::class));
    }
}
