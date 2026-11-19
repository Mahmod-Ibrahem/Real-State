<?php

namespace Modules\Property\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyClassifications;
use Modules\Property\Models\PropertyType;
use Modules\Property\Observers\PropertyClassificationObserver;
use Modules\Property\Observers\PropertyObserver;
use Modules\Property\Observers\PropertyTypeObserver;

class PropertyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path('Property', 'Database/Migrations'));

        // Observers forget the cached public lists when these models change
        Property::observe(PropertyObserver::class);
        PropertyType::observe(PropertyTypeObserver::class);
        PropertyClassifications::observe(PropertyClassificationObserver::class);
    }
}
