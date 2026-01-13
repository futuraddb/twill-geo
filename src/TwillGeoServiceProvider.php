<?php

namespace futura\TwillGeo;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class TwillGeoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap your package's services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadViewsFrom(__DIR__ . '/../views', 'twill-geo');
        Blade::componentNamespace('futura\\TwillGeo\\View\\Components', 'twill-geo');

        $this->publishes([
            __DIR__.'/../config/twill-geo.php' => config_path('twill-geo.php'),
        ], 'twill-geo-config');
        $this->publishes([
            __DIR__.'/../assets' => resource_path('assets'),
        ], 'twill-geo-assets');
    }

    /**
     * Register any application services.
     */

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/twill-geo.php', 'twill-geo'
        );
    }
}
