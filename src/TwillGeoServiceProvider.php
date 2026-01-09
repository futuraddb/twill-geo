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
        $this->loadViewsFrom(__DIR__ . '/../views', 'twill-geo');
        Blade::componentNamespace('futura\\TwillGeo\\View\\Components', 'twill-geo');

        $this->publishes([
            __DIR__.'/../config/twill-geo.php' => config_path('twill-geo.php'),
        ], 'twill-geo-config');
        $this->publishes([
            __DIR__.'/../assets' => resource_path('assets'),
        ], 'twill-assets');
        // readme:
        // php artisan vendor:publish --tag=twill-assets
        // php artisan twill:build
        // php artisan migrate (if you declined migration in a twill:build step)
        // add ->addFieldset(GeoFormFieldset::getFieldset()) into Twill's form controller
        // add <x-twill-geo::snippet :item="$item ?? null" /> to layout
        // upgrade:
        //      - llm model selector in Twill's form field
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
