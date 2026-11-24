<?php

namespace Tests\Feature\Users;

use App\Models\User;
use App\Services\OwnerRegistrationService;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Modules\Property\Models\Property;
use Tests\TestCase;

class AccountFlagsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(PermissionSeeder::class);
    }

    public function test_disabled_accounts_cannot_log_in_on_either_login_page(): void
    {
        $user = User::factory()->inactive()->create()->assignRole(OwnerRegistrationService::ROLE);

        foreach (['login', 'supplier.login'] as $login) {
            $this->post(route($login), ['email' => $user->email, 'password' => 'password'])
                ->assertSessionHasErrors(['email' => __('Your account is disabled.')]);
            $this->assertGuest();
        }
    }

    public function test_login_is_locked_after_too_many_failed_attempts(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 5) as $attempt) {
            $this->post(route('login'), ['email' => $user->email, 'password' => 'wrong'])
                ->assertSessionHasErrors(['email' => __('auth.failed')]);
        }

        $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])->assertTooManyRequests();
        $this->assertGuest();
    }

    public function test_a_signed_in_user_is_logged_out_once_disabled(): void
    {
        $supplier = User::factory()->create()->assignRole(OwnerRegistrationService::ROLE);
        $this->actingAs($supplier)->get(route('supplier.dashboard'))->assertOk();

        $supplier->update(['is_active' => false]);

        $this->get(route('supplier.dashboard'))->assertRedirect(route('supplier.login'));
        $this->assertGuest();
    }

    public function test_new_suppliers_start_under_review(): void
    {
        $this->post(route('supplier.register'), [
            'name' => 'Sara Owner',
            'email' => 'sara@example.com',
            'phone_country' => 'EG',
            'phone' => '01001234567',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('supplier.dashboard'));

        $this->assertTrue(User::firstWhere('email', 'sara@example.com')->under_review);
    }

    public function test_properties_of_an_owner_under_review_are_not_public(): void
    {
        $owner = User::factory()->underReview()->create()->assignRole(OwnerRegistrationService::ROLE);
        $hidden = Property::factory()->ownedBy($owner)->create(['is_published' => true]);
        $shown = Property::factory()->ownedBy(User::factory()->create())->create(['is_published' => true]);

        $this->assertEqualsCanonicalizing([$shown->id], Property::active()->pluck('id')->all());

        $owner->update(['under_review' => false]);

        $this->assertEqualsCanonicalizing([$hidden->id, $shown->id], Property::active()->pluck('id')->all());
    }

    public function test_clearing_the_review_forgets_the_cached_public_lists(): void
    {
        $owner = User::factory()->underReview()->create();
        Cache::forever('cities', ['stale']);

        $owner->update(['under_review' => false]);

        $this->assertFalse(Cache::has('cities'));
    }
}
