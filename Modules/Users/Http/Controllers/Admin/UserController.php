<?php

namespace Modules\Users\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Users\Enums\AccountReview;
use Modules\Users\Enums\AccountStatus;
use Modules\Users\Http\Requests\IndexUserRequest;
use Modules\Users\Http\Requests\StoreUserRequest;
use Modules\Users\Http\Requests\UpdateUserRequest;
use Modules\Users\Http\Resources\RoleResource;
use Modules\Users\Http\Resources\UserListResource;
use Modules\Users\Services\RoleService;
use Modules\Users\Services\UserService;

final class UserController extends Controller
{
    public function __construct(
        private readonly UserService $users,
        private readonly RoleService $roles,
    ) {
    }

    public function index(IndexUserRequest $request): Response
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => UserListResource::collection($this->users->paginateForAdmin($request->filters())),
            'roles' => RoleResource::collection($this->roles->assignable()),
            'statuses' => AccountStatus::options(),
            'reviews' => AccountReview::options(),
            'filters' => $request->activeFilters(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->users->create($request->validated());

        return back();
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->users->update($user, $request->validated(), $request->user());

        return back();
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->users->delete($user, $request->user());

        return back();
    }
}
