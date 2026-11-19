<?php

namespace App\Providers;

use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Route::admin()->group(…): the back-office group every module uses (CLAUDE.md, Routing).
        // Registered here, not in boot(), because module route files load while providers boot.
        Route::macro('admin', fn (): RouteRegistrar => Route::middleware(['auth', 'role:Super Admin'])
            ->prefix('admin')
            ->name('admin.'));
    }

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
