<?php

namespace App\Http\Middleware;

use App\Services\ImpersonationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function __construct(private readonly ImpersonationService $impersonation)
    {
    }

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
        return $request->is('admin', 'admin/*', 'supplier', 'supplier/*', 'notifications/*', 'impersonation/*');
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'permissions' => fn () => $request->user()?->getAllPermissions()->pluck('name') ?? [],
            ],
            'impersonating' => fn () => $this->impersonation->isImpersonating(),
            'flash' => [
                'message' => fn() => $request->session()->get('message'),
                'error' => fn() => $request->session()->get('error'),
                'contact_url' => fn () => $request->session()->get('contact_url'),
            ],
            'favouriteProperty' => fn() => json_decode(Cookie::get('favourite', '[]'), true),
            'locale' => app()->getLocale(),
        ];
    }
}
