<?php

namespace Modules\Users\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Modules\Users\Http\Requests\UserAccountStateRequest;
use Modules\Users\Services\UserService;

final class UserAccountStateController extends Controller
{
    public function __construct(private readonly UserService $users)
    {
    }

    public function __invoke(UserAccountStateRequest $request, User $user): RedirectResponse
    {
        $this->users->updateAccountState($user, $request->validated(), $request->user());

        return back();
    }
}
