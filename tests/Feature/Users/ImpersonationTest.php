<?php

namespace Tests\Feature\Users;

use App\Events\ImpersonationEnded;
use App\Events\ImpersonationStarted;
use App\Models\User;
use App\Services\ImpersonationService;
use App\Services\OwnerRegistrationService;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Modules\Dashboard\Enums\DashboardPermission;
use Modules\Users\Enums\UserPermission;
use Tests\TestCase;

class ImpersonationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(PermissionSeeder::class);
    }

    public function test_an_admin_logs_in_as_a_supplier_and_returns_to_their_own_account(): void
    {
        Event::fake([ImpersonationStarted::class, ImpersonationEnded::class]);
        $admin = $this->admin();
        $supplier = $this->supplier();

        $this->actingAs($admin)->post(route('admin.users.impersonate', $supplier))
            ->assertRedirect(route('supplier.dashboard'));
        $this->assertAuthenticatedAs($supplier);
        $this->assertSame($admin->id, session(ImpersonationService::SESSION_KEY));
        $this->get(route('supplier.dashboard'))->assertInertia(fn ($page) => $page->where('impersonating', true));
        Event::assertDispatched(ImpersonationStarted::class, fn ($event) => $event->impersonator->is($admin) && $event->user->is($supplier));

        $this->post(route('impersonation.leave'))->assertRedirect(route('admin.users.index'));
        $this->assertAuthenticatedAs($admin);
        $this->assertFalse(session()->has(ImpersonationService::SESSION_KEY));
        Event::assertDispatched(ImpersonationEnded::class, fn ($event) => $event->impersonator->is($admin) && $event->user->is($supplier));
    }

    public function test_only_active_suppliers_other_than_yourself_can_be_impersonated(): void
    {
        $admin = $this->admin();
        $staff = User::factory()->create()->givePermissionTo(DashboardPermission::View->value);
        $superAdmin = User::factory()->create()->assignRole(PermissionSeeder::SUPER_ADMIN);
        $disabled = User::factory()->inactive()->create()->assignRole(OwnerRegistrationService::ROLE);

        foreach ([$admin, $staff, $superAdmin, $disabled] as $target) {
            $this->actingAs($admin)->post(route('admin.users.impersonate', $target))->assertSessionHasErrors('database');
            $this->assertAuthenticatedAs($admin);
            $this->assertFalse(session()->has(ImpersonationService::SESSION_KEY));
        }
    }

    public function test_impersonation_cannot_be_nested_and_leaving_needs_an_impersonation(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('impersonation.leave'))->assertSessionHasErrors('database');

        $this->actingAs($admin)->post(route('admin.users.impersonate', $this->supplier()));
        $this->actingAs($admin)->post(route('admin.users.impersonate', $this->supplier()))
            ->assertSessionHasErrors(['database' => __('Return to your own account first.')]);
    }

    public function test_users_without_the_permission_cannot_impersonate(): void
    {
        $staff = User::factory()->create()->givePermissionTo(UserPermission::View->value);

        $this->actingAs($staff)->post(route('admin.users.impersonate', $this->supplier()))->assertForbidden();
    }

    public function test_actions_in_the_users_name_are_blocked_while_impersonating(): void
    {
        Notification::fake();
        $supplier = User::factory()->unverified()->create(['phone' => '+201001234567', 'phone_country' => 'EG'])
            ->assignRole(OwnerRegistrationService::ROLE);

        $this->actingAs($this->admin())->post(route('admin.users.impersonate', $supplier));

        $blocked = __("You can't do this while logged in as another user.");
        $this->post(route('supplier.phone.code'))->assertSessionHasErrors(['database' => $blocked]);
        $this->post(route('supplier.email.code'))->assertSessionHasErrors(['database' => $blocked]);
        Notification::assertNothingSent();
    }

    public function test_the_users_table_marks_who_can_be_impersonated(): void
    {
        $admin = $this->admin();
        $supplier = $this->supplier();
        $staff = User::factory()->create()->givePermissionTo(DashboardPermission::View->value);

        $expected = collect([$admin->id => false, $supplier->id => true, $staff->id => false])->sortKeys()->all();

        $this->actingAs($admin)->get(route('admin.users.index'))->assertInertia(fn ($page) => $page->where(
            'users.data',
            fn ($rows) => collect($rows)->pluck('can_be_impersonated', 'id')->sortKeys()->all() === $expected,
        ));
    }

    private function admin(): User
    {
        return User::factory()->create()->givePermissionTo([
            DashboardPermission::View->value,
            UserPermission::View->value,
            UserPermission::Impersonate->value,
        ]);
    }

    private function supplier(): User
    {
        return User::factory()->create()->assignRole(OwnerRegistrationService::ROLE);
    }
}
