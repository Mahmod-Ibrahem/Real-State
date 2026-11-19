<?php

namespace Modules\Content\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Content\Models\BasicContent;
use Modules\Content\Models\Broker;
use Modules\Content\Observers\BasicContentObserver;
use Modules\Content\Observers\BrokerObserver;

class ContentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path('Content', 'Database/Migrations'));

        // Observers forget the cached public lists when these models change
        BasicContent::observe(BasicContentObserver::class);
        Broker::observe(BrokerObserver::class);
    }
}
