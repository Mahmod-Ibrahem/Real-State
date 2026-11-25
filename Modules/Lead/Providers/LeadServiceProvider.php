<?php

namespace Modules\Lead\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Lead\Events\PropertyLeadReceived;
use Modules\Lead\Listeners\NotifyAdminsOfNewLead;
use Modules\Lead\Listeners\NotifyOwnerOfNewLead;
use Modules\Lead\Models\PropertyLead;
use Modules\Lead\Policies\PropertyLeadPolicy;

class LeadServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path('Lead', 'Database/Migrations'));

        Gate::policy(PropertyLead::class, PropertyLeadPolicy::class);

        Event::listen(PropertyLeadReceived::class, NotifyOwnerOfNewLead::class);
        Event::listen(PropertyLeadReceived::class, NotifyAdminsOfNewLead::class);
    }
}
