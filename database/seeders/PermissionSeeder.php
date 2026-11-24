<?php

namespace Database\Seeders;

use App\Enums\NotificationPermission;
use App\Services\OwnerRegistrationService;
use BackedEnum;
use Illuminate\Database\Seeder;
use Modules\Content\Enums\ContentPermission;
use Modules\Dashboard\Enums\DashboardPermission;
use Modules\Lead\Enums\LeadPermission;
use Modules\Location\Enums\LocationPermission;
use Modules\Property\Enums\PropertyPermission;
use Modules\Users\Enums\UserPermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public const SUPER_ADMIN = 'Super Admin';

    /** @var list<class-string<BackedEnum>> */
    private const PERMISSION_ENUMS = [
        NotificationPermission::class,
        DashboardPermission::class,
        PropertyPermission::class,
        LocationPermission::class,
        LeadPermission::class,
        ContentPermission::class,
        UserPermission::class,
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $added = $this->createMissingPermissions();

        Role::findOrCreate(self::SUPER_ADMIN)->syncPermissions(Permission::all());

        // Roles edited later keep their permissions on re-runs and only gain the ones added since
        foreach ($this->defaultRoles() as $name => $defaults) {
            $role = Role::findOrCreate($name);
            $role->wasRecentlyCreated
                ? $role->syncPermissions($defaults)
                : $role->givePermissionTo(array_values(array_intersect($defaults, $added)));
        }
    }

    /** @return list<string> */
    private function createMissingPermissions(): array
    {
        return collect(self::PERMISSION_ENUMS)
            ->flatMap(fn (string $enum) => $enum::cases())
            ->map(fn (BackedEnum $permission) => Permission::findOrCreate($permission->value))
            ->filter(fn (Permission $permission) => $permission->wasRecentlyCreated)
            ->pluck('name')
            ->all();
    }

    /** @return array<string, list<string>> */
    private function defaultRoles(): array
    {
        return [
            OwnerRegistrationService::ROLE => $this->values([
                ...PropertyPermission::forOwners(),
                NotificationPermission::Receive,
            ]),
        ];
    }

    /**
     * @param list<BackedEnum> $permissions
     * @return list<string>
     */
    private function values(array $permissions): array
    {
        return array_map(fn (BackedEnum $permission) => $permission->value, $permissions);
    }
}
