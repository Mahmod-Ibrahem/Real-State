<?php

namespace Modules\Users\Services;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Users\Enums\AccountReview;
use Modules\Users\Enums\AccountStatus;
use Modules\Users\Exceptions\OwnAccountCannotBeDisabled;
use Modules\Users\Exceptions\OwnRoleCannotChange;
use Modules\Users\Exceptions\UserCannotBeDeleted;
use Modules\Users\Exceptions\UserIsProtected;

final class UserService
{
    private const PER_PAGE = 10;

    /** @param array{search: ?string, role_id: ?int, status: ?AccountStatus, review: ?AccountReview} $filters */
    public function paginateForAdmin(array $filters): LengthAwarePaginator
    {
        ['search' => $search, 'role_id' => $roleId, 'status' => $status, 'review' => $review] = $filters;

        return User::query()
            ->with(['roles.permissions', 'permissions'])
            ->withCount('properties')
            ->whereDoesntHave('roles', fn (Builder $query) => $query->where('name', PermissionSeeder::SUPER_ADMIN))
            ->when($search, fn (Builder $query) => $query->where(fn (Builder $query) => $query
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")))
            ->when($roleId, fn (Builder $query) => $query->whereRelation('roles', 'id', $roleId))
            ->when($status, fn (Builder $query) => $query->where('is_active', $status->isActive()))
            ->when($review, fn (Builder $query) => $query->where('under_review', $review->isUnderReview()))
            ->latest()
            ->paginate(self::PER_PAGE)
            ->withQueryString();
    }

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::create(Arr::except($data, ['role_id']));
            $user->syncRoles([(int) $data['role_id']]);

            return $user;
        });
    }

    public function update(User $user, array $data, User $actor): User
    {
        $this->ensureNotProtected($user);

        if ($user->is($actor) && $this->changesRole($user, (int) $data['role_id'])) {
            throw OwnRoleCannotChange::make();
        }

        return DB::transaction(function () use ($user, $data): User {
            $user->update($this->attributesFrom($data));
            $user->syncRoles([(int) $data['role_id']]);

            return $user;
        });
    }

    /** @param array{is_active?: bool, under_review?: bool} $state */
    public function updateAccountState(User $user, array $state, User $actor): void
    {
        $this->ensureNotProtected($user);

        if ($user->is($actor) && ($state['is_active'] ?? true) === false) {
            throw OwnAccountCannotBeDisabled::make();
        }

        $user->update($state);
    }

    public function delete(User $user, User $actor): void
    {
        $this->ensureNotProtected($user);

        if ($user->is($actor)) {
            throw UserCannotBeDeleted::self();
        }

        if ($user->properties()->exists()) {
            throw UserCannotBeDeleted::hasProperties();
        }

        $user->delete();
    }

    private function ensureNotProtected(User $user): void
    {
        if ($user->hasRole(PermissionSeeder::SUPER_ADMIN)) {
            throw UserIsProtected::make();
        }
    }

    private function changesRole(User $user, int $roleId): bool
    {
        return $user->roles()->pluck('id')->all() !== [$roleId];
    }

    private function attributesFrom(array $data): array
    {
        $ignored = filled($data['password'] ?? null) ? ['role_id'] : ['role_id', 'password'];

        return Arr::except($data, $ignored);
    }
}
