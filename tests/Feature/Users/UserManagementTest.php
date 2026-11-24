<?php

namespace Tests\Feature\Users;

use App\Models\User;
use App\Services\OwnerRegistrationService;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Property\Models\Property;
use Modules\Users\Enums\UserPermission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private const STAFF_ROLE = 'Staff';

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(PermissionSeeder::class);
        $this->admin = User::factory()->create(['name' => 'Admin'])->assignRole(PermissionSeeder::SUPER_ADMIN);
    }

    public function test_the_list_searches_and_filters_by_role(): void
    {
        $sara = $this->supplier(['name' => 'Sara Owner', 'email' => 'sara@example.com']);
        $this->supplier(['name' => 'Omar Owner']);

        $this->actingAs($this->admin)->get(route('admin.users.index', ['search' => 'sara@']))
            ->assertInertia(fn ($page) => $page->component('Admin/Users/Index')
                ->has('users.data', 1)
                ->where('users.data.0.id', $sara->id)
                ->where('users.data.0.role_name', OwnerRegistrationService::ROLE));

        $this->actingAs($this->admin)->get(route('admin.users.index', ['role' => $this->ownerRole()->id]))
            ->assertInertia(fn ($page) => $page->has('users.data', 2)->where('filters.role', $this->ownerRole()->id));
    }

    public function test_the_list_filters_by_status_and_review(): void
    {
        $disabled = User::factory()->inactive()->create()->assignRole(OwnerRegistrationService::ROLE);
        $pending = User::factory()->underReview()->create()->assignRole(OwnerRegistrationService::ROLE);
        $this->supplier();

        $this->actingAs($this->admin)->get(route('admin.users.index', ['status' => 'disabled']))
            ->assertInertia(fn ($page) => $page->has('users.data', 1)->where('users.data.0.id', $disabled->id)
                ->where('filters.status', 'disabled'));

        $this->actingAs($this->admin)->get(route('admin.users.index', ['review' => 'under_review']))
            ->assertInertia(fn ($page) => $page->has('users.data', 1)->where('users.data.0.id', $pending->id));
    }

    public function test_admins_switch_the_account_flags_from_the_table(): void
    {
        $user = $this->supplier();

        $this->actingAs($this->admin)->patch(route('admin.users.account', $user), ['is_active' => false])
            ->assertSessionHasNoErrors();
        $this->actingAs($this->admin)->patch(route('admin.users.account', $user), ['under_review' => true])
            ->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertFalse($user->is_active);
        $this->assertTrue($user->under_review);
    }

    public function test_staff_cannot_disable_themselves_and_super_admins_cannot_be_switched(): void
    {
        $staff = $this->staff();
        $other = User::factory()->create()->assignRole(PermissionSeeder::SUPER_ADMIN);

        $this->actingAs($staff)->patch(route('admin.users.account', $staff), ['is_active' => false])
            ->assertSessionHasErrors('database');
        $this->actingAs($this->admin)->patch(route('admin.users.account', $other), ['is_active' => false])
            ->assertSessionHasErrors('database');

        $this->assertTrue($staff->fresh()->is_active);
        $this->assertTrue($other->fresh()->is_active);
    }

    public function test_super_admins_and_their_role_are_hidden(): void
    {
        $this->supplier();

        $this->actingAs($this->admin)->get(route('admin.users.index'))
            ->assertInertia(fn ($page) => $page
                ->has('users.data', 1)
                ->where('users.data.0.role_name', OwnerRegistrationService::ROLE)
                ->where('roles.data', fn ($roles) => collect($roles)->pluck('name')->doesntContain(PermissionSeeder::SUPER_ADMIN)));

        $superAdminRole = Role::findByName(PermissionSeeder::SUPER_ADMIN);
        $this->actingAs($this->admin)->get(route('admin.users.index', ['role' => $superAdminRole->id]))
            ->assertSessionHasErrors('role');
    }

    public function test_the_super_admin_role_cannot_be_assigned(): void
    {
        $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'Sneaky',
            'email' => 'sneaky@example.com',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
            'role_id' => Role::findByName(PermissionSeeder::SUPER_ADMIN)->id,
        ])->assertSessionHasErrors('role_id');

        $this->assertDatabaseMissing('users', ['email' => 'sneaky@example.com']);
    }

    public function test_super_admin_accounts_cannot_be_edited_or_deleted(): void
    {
        $other = User::factory()->create()->assignRole(PermissionSeeder::SUPER_ADMIN);

        $this->actingAs($this->admin)->put(route('admin.users.update', $other), [
            'name' => 'Renamed',
            'email' => $other->email,
            'role_id' => $this->ownerRole()->id,
        ])->assertSessionHasErrors('database');
        $this->actingAs($this->admin)->delete(route('admin.users.destroy', $other))->assertSessionHasErrors('database');

        $this->assertNotSame('Renamed', $other->fresh()->name);
        $this->assertTrue($other->fresh()->hasRole(PermissionSeeder::SUPER_ADMIN));
    }

    public function test_users_without_permission_cannot_see_the_list(): void
    {
        $this->actingAs($this->supplier())->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_creating_a_user_hashes_the_password_and_assigns_the_role(): void
    {
        $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'New Staff',
            'email' => 'staff@example.com',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
            'role_id' => $this->ownerRole()->id,
        ])->assertSessionHasNoErrors();

        $user = User::firstWhere('email', 'staff@example.com');
        $this->assertTrue(Hash::check('secret-password', $user->password));
        $this->assertTrue($user->hasRole(OwnerRegistrationService::ROLE));
    }

    public function test_emails_must_be_unique(): void
    {
        $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'Copy',
            'email' => $this->admin->email,
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
            'role_id' => $this->ownerRole()->id,
        ])->assertSessionHasErrors('email');

        $other = $this->supplier();
        $this->actingAs($this->admin)->put(route('admin.users.update', $other), [
            'name' => $other->name,
            'email' => $this->admin->email,
            'role_id' => $this->ownerRole()->id,
        ])->assertSessionHasErrors('email');
    }

    public function test_updating_with_a_blank_password_keeps_the_old_one(): void
    {
        $user = $this->supplier();
        $oldHash = $user->password;

        $this->actingAs($this->admin)->put(route('admin.users.update', $user), [
            'name' => 'Renamed',
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
            'role_id' => $this->ownerRole()->id,
        ])->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertSame('Renamed', $user->name);
        $this->assertSame($oldHash, $user->password);
    }

    public function test_staff_cannot_change_their_own_role(): void
    {
        $staff = $this->staff();

        $this->actingAs($staff)->put(route('admin.users.update', $staff), [
            'name' => $staff->name,
            'email' => $staff->email,
            'role_id' => $this->ownerRole()->id,
        ])->assertSessionHasErrors('database');

        $this->assertTrue($staff->fresh()->hasRole(self::STAFF_ROLE));
    }

    public function test_deleting_is_refused_for_yourself_and_for_property_owners(): void
    {
        $staff = $this->staff();
        $owner = $this->supplier();
        Property::factory()->ownedBy($owner)->create();

        $this->actingAs($staff)->delete(route('admin.users.destroy', $staff))->assertSessionHasErrors('database');
        $this->actingAs($this->admin)->delete(route('admin.users.destroy', $owner))->assertSessionHasErrors('database');

        $this->assertModelExists($staff);
        $this->assertModelExists($owner);
    }

    public function test_a_user_without_properties_can_be_deleted(): void
    {
        $user = $this->supplier();

        $this->actingAs($this->admin)->delete(route('admin.users.destroy', $user))->assertSessionHasNoErrors();

        $this->assertModelMissing($user);
    }

    private function staff(): User
    {
        $role = Role::findOrCreate(self::STAFF_ROLE)->givePermissionTo(array_column(UserPermission::cases(), 'value'));

        return User::factory()->create()->assignRole($role);
    }

    private function supplier(array $attributes = []): User
    {
        return User::factory()->create($attributes)->assignRole(OwnerRegistrationService::ROLE);
    }

    private function ownerRole(): Role
    {
        return Role::findByName(OwnerRegistrationService::ROLE);
    }
}
