<?php

namespace Modules\Users\Services;

use Database\Seeders\PermissionSeeder;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

final class RoleService
{
    public function assignable(): Collection
    {
        return Role::query()->where('name', '!=', PermissionSeeder::SUPER_ADMIN)->orderBy('name')->get();
    }
}
