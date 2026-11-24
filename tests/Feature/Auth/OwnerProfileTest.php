<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\OwnerRegistrationService;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(PermissionSeeder::class);
    }

    public function test_suppliers_see_their_details_and_whether_their_phone_is_verified(): void
    {
        $supplier = User::factory()->create(['name' => 'Sara Owner', 'phone' => '+201001234567', 'phone_country' => 'EG'])
            ->assignRole(OwnerRegistrationService::ROLE);

        $this->actingAs($supplier)->get(route('supplier.profile.show'))
            ->assertInertia(fn ($page) => $page->component('Owner/Profile/Show')->where('profile', [
                'name' => 'Sara Owner',
                'email' => $supplier->email,
                'phone' => '+201001234567',
                'phone_verified' => false,
            ]));

        $supplier->forceFill(['phone_verified_at' => now()])->save();

        $this->actingAs($supplier)->get(route('supplier.profile.show'))
            ->assertInertia(fn ($page) => $page->where('profile.phone_verified', true));
    }

    public function test_a_supplier_without_a_phone_sees_none(): void
    {
        $supplier = User::factory()->create()->assignRole(OwnerRegistrationService::ROLE);

        $this->actingAs($supplier)->get(route('supplier.profile.show'))
            ->assertInertia(fn ($page) => $page->where('profile.phone', null)->where('profile.phone_verified', false));
    }

    public function test_guests_go_to_the_supplier_login_and_staff_are_kept_out(): void
    {
        $this->get(route('supplier.profile.show'))->assertRedirect(route('supplier.login'));

        $this->actingAs(User::factory()->create())->get(route('supplier.profile.show'))->assertForbidden();
    }
}
