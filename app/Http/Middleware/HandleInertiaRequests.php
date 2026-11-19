<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * The admin (/admin/*) has its own Vite bundle without Bootstrap; see ADMIN_UI.md.
     */
    public function rootView(Request $request): string
    {
        return $this->isAdmin($request) ? 'admin' : 'app';
    }

    /**
     * A different version per bundle forces a full page load when navigating between the site and the
     * admin, so the two bundles (and their CSS) never mix in one page.
     */
    public function version(Request $request): ?string
    {
        return ($this->isAdmin($request) ? 'admin-' : 'site-') . parent::version($request);
    }

    private function isAdmin(Request $request): bool
    {
        return $request->is('admin', 'admin/*');
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
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
