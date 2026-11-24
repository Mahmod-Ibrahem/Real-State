<?php

namespace Modules\Users\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ImpersonationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ImpersonateUserController extends Controller
{
    public function __construct(private readonly ImpersonationService $impersonation)
    {
    }

    public function __invoke(Request $request, User $user): RedirectResponse
    {
        $this->impersonation->start($request->user(), $user);

        return to_route($user->dashboardRoute());
    }
}
