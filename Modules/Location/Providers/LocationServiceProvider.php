<?php

namespace Modules\Location\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Location\Models\Area;
use Modules\Location\Models\City;
use Modules\Location\Models\Country;
use Modules\Location\Observers\AreaObserver;
use Modules\Location\Observers\CityObserver;
use Modules\Location\Observers\CountryObserver;

class LocationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path('Location', 'Database/Migrations'));

        // Observers forget the cached public lists when these models change
        Country::observe(CountryObserver::class);
        City::observe(CityObserver::class);
        Area::observe(AreaObserver::class);
    }
}
