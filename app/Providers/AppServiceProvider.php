<?php

namespace App\Providers;

use App\Models\Area;
use App\Models\BasicContent;
use App\Models\Broker;
use App\Models\City;
use App\Models\Country;
use App\Models\Property;
use App\Models\PropertyClassifications;
use App\Models\PropertyType;
use App\Observers\AreaObserver;
use App\Observers\BasicContentObserver;
use App\Observers\BrokerObserver;
use App\Observers\CityObserver;
use App\Observers\CountryObserver;
use App\Observers\PropertyClassificationObserver;
use App\Observers\PropertyObserver;
use App\Observers\PropertyTypeObserver;
use Illuminate\Support\Facades\Vite;
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
        Vite::prefetch(concurrency: 3);
        Property::observe(PropertyObserver::class);
        PropertyType::observe(PropertyTypeObserver::class);
        PropertyClassifications::observe(PropertyClassificationObserver::class);
        BasicContent::observe(BasicContentObserver::class);
        Broker::observe(BrokerObserver::class);
        City::observe(CityObserver::class);
        Area::observe(AreaObserver::class);
        Country::observe(CountryObserver::class);
        Broker::observe(BrokerObserver::class);
    }
}
