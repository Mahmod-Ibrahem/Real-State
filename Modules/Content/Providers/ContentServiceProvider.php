<?php

namespace Modules\Content\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Content\Models\Faq;
use Modules\Content\Observers\FaqObserver;

class ContentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path('Content', 'Database/Migrations'));

        Faq::observe(FaqObserver::class);
    }
}
