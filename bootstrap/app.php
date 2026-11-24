<?php

use App\Exceptions\BusinessRuleException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\LocaleMiddleware::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
            \App\Http\Middleware\EnsureAccountIsActive::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->redirectUsersTo(fn (Request $request) => route($request->user()->dashboardRoute()));
        $middleware->redirectGuestsTo(
            fn (Request $request) => $request->is('supplier', 'supplier/*') ? route('supplier.login') : route('login')
        );

        $middleware->alias([
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(
            fn (BusinessRuleException $exception) => back()->withErrors(['database' => $exception->getMessage()])
        );
        $exceptions->render(fn (ThrottleRequestsException $exception, Request $request) => $request->expectsJson()
            ? null
            : back()->withErrors(['database' => __('Too many attempts. Please try again in :seconds seconds.', [
                'seconds' => $exception->getHeaders()['Retry-After'] ?? 60,
            ])])
        );
    })->create();
