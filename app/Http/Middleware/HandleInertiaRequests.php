<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function rootView(Request $request): string
    {
        return $this->usesDashboardBundle($request) ? 'admin' : 'app';
    }

    // A different version per bundle forces a full page load between the site and the dashboards
    public function version(Request $request): ?string
    {
        return ($this->usesDashboardBundle($request) ? 'admin-' : 'site-') . parent::version($request);
    }

    private function usesDashboardBundle(Request $request): bool
    {
        return $request->is('admin', 'admin/*', 'owner', 'owner/*');
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'permissions' => fn () => $request->user()?->getAllPermissions()->pluck('name') ?? [],
                'unread_notifications' => fn () => $request->user()?->unreadNotifications()->count() ?? 0,
            ],
            'flash' => [
                'message' => fn() => $request->session()->get('message'),
                'error' => fn() => $request->session()->get('error'),
            ],
            'favouriteProperty' => fn() => json_decode(Cookie::get('favourite', '[]'), true),
            'locale' => app()->getLocale(),
        ];
    }
}
