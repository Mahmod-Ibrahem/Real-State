<?php

namespace Modules\Property\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Property\Events\PropertyApproved;
use Modules\Property\Events\PropertySubmitted;
use Modules\Property\Listeners\NotifyAdminsOfSubmittedProperty;
use Modules\Property\Listeners\NotifyOwnerOfApprovedProperty;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyClassifications;
use Modules\Property\Models\PropertyType;
use Modules\Property\Observers\PropertyClassificationObserver;
use Modules\Property\Observers\PropertyObserver;
use Modules\Property\Observers\PropertyTypeObserver;
use Modules\Property\Policies\PropertyPolicy;

class PropertyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path('Property', 'Database/Migrations'));

        Property::observe(PropertyObserver::class);
        PropertyType::observe(PropertyTypeObserver::class);
        PropertyClassifications::observe(PropertyClassificationObserver::class);

        Gate::policy(Property::class, PropertyPolicy::class);

        Event::listen(PropertySubmitted::class, NotifyAdminsOfSubmittedProperty::class);
        Event::listen(PropertyApproved::class, NotifyOwnerOfApprovedProperty::class);
    }
}
