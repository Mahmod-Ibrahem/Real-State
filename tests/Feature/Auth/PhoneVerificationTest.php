<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\OwnerRegistrationService;
use App\Services\PhoneVerification\LogPhoneVerifier;
use App\Services\PhoneVerification\PhoneVerifier;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PhoneVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(PermissionSeeder::class);
        $this->app->instance(PhoneVerifier::class, $this->app->make(LogPhoneVerifier::class));
    }

    public function test_sign_up_stores_the_mobile_number_in_e164(): void
    {
        $this->post(route('supplier.register'), $this->signUp(['phone' => '0100 123 4567']))
            ->assertRedirect(route('supplier.dashboard'));

        $supplier = User::firstWhere('email', 'sara@example.com');
        $this->assertSame('+201001234567', $supplier->phone);
        $this->assertSame('EG', $supplier->phone_country);
        $this->assertFalse($supplier->hasVerifiedPhone());
    }

    public function test_sign_up_rejects_landlines_and_numbers_from_another_country(): void
    {
        $this->post(route('supplier.register'), $this->signUp(['phone' => '02 2345 6789']))->assertSessionHasErrors('phone');
        $this->post(route('supplier.register'), $this->signUp(['phone' => '0501234567']))->assertSessionHasErrors('phone');

        $this->assertGuest();
    }

    public function test_the_same_number_typed_another_way_is_taken(): void
    {
        User::factory()->create(['phone' => '+201001234567', 'phone_country' => 'EG']);

        $this->post(route('supplier.register'), $this->signUp(['phone' => '+20 100 123 4567']))
            ->assertSessionHasErrors(['phone' => __('This phone number is already registered.')]);
    }

    public function test_a_supplier_verifies_their_phone_with_the_texted_code(): void
    {
        $supplier = $this->supplier();
        $code = null;
        Log::listen(function ($message) use (&$code) {
            preg_match('/: (\d{6})$/', $message->message, $matches) && $code = $matches[1];
        });

        $this->actingAs($supplier)->post(route('supplier.phone.code'))->assertSessionHasNoErrors();
        $this->assertNotNull($code);

        $this->actingAs($supplier)->post(route('supplier.phone.verify'), ['code' => $code])->assertSessionHasNoErrors();

        $this->assertTrue($supplier->fresh()->hasVerifiedPhone());
    }

    public function test_a_wrong_code_is_refused(): void
    {
        $supplier = $this->supplier();

        $this->actingAs($supplier)->post(route('supplier.phone.code'));
        $this->actingAs($supplier)->post(route('supplier.phone.verify'), ['code' => '000000'])
            ->assertSessionHasErrors(['code' => __('That code is wrong or has expired.')]);

        $this->assertFalse($supplier->fresh()->hasVerifiedPhone());
    }

    public function test_sending_codes_is_throttled(): void
    {
        $supplier = $this->supplier();

        foreach (range(1, 3) as $attempt) {
            $this->actingAs($supplier)->post(route('supplier.phone.code'))->assertSessionHasNoErrors();
        }

        $this->actingAs($supplier)->post(route('supplier.phone.code'))->assertSessionHasErrors('database');
        $this->actingAs($supplier)->postJson(route('supplier.phone.code'))->assertTooManyRequests();
    }

    public function test_the_dashboard_asks_only_unverified_suppliers_to_verify(): void
    {
        $supplier = $this->supplier();

        $this->actingAs($supplier)->get(route('supplier.dashboard'))
            ->assertInertia(fn ($page) => $page->where('unverified_phone', true));

        $supplier->forceFill(['phone_verified_at' => now()])->save();

        $this->actingAs($supplier)->get(route('supplier.dashboard'))
            ->assertInertia(fn ($page) => $page->where('unverified_phone', false));
    }

    private function signUp(array $overrides = []): array
    {
        return [
            'name' => 'Sara Owner',
            'email' => 'sara@example.com',
            'phone_country' => 'EG',
            'phone' => '01001234567',
            'password' => 'password',
            'password_confirmation' => 'password',
            ...$overrides,
        ];
    }

    private function supplier(): User
    {
        return User::factory()->create(['phone' => '+201001234567', 'phone_country' => 'EG'])
            ->assignRole(OwnerRegistrationService::ROLE);
    }
}
