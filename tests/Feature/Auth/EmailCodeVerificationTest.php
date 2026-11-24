<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\EmailVerificationCodeNotification;
use App\Services\OwnerRegistrationService;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailCodeVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(PermissionSeeder::class);
        Notification::fake();
    }

    public function test_sign_up_does_not_send_a_verification_link(): void
    {
        $this->post(route('supplier.register'), [
            'name' => 'Sara Owner',
            'email' => 'sara@example.com',
            'phone_country' => 'EG',
            'phone' => '01001234567',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $supplier = User::firstWhere('email', 'sara@example.com');
        $this->assertFalse($supplier->hasVerifiedEmail());
        Notification::assertNothingSentTo($supplier);
    }

    public function test_a_supplier_verifies_their_email_with_the_mailed_code(): void
    {
        $supplier = $this->supplier();

        $this->actingAs($supplier)->post(route('supplier.email.code'))->assertSessionHasNoErrors();

        $code = null;
        Notification::assertSentTo(
            $supplier,
            EmailVerificationCodeNotification::class,
            function (EmailVerificationCodeNotification $notification) use ($supplier, &$code) {
                preg_match('/\*\*(\d{6})\*\*/', implode("\n", $notification->toMail($supplier)->introLines), $matches);
                $code = $matches[1] ?? null;

                return $code !== null;
            },
        );

        $this->actingAs($supplier)->post(route('supplier.email.verify'), ['code' => $code])->assertSessionHasNoErrors();

        $this->assertTrue($supplier->fresh()->hasVerifiedEmail());
    }

    public function test_a_wrong_email_code_is_refused(): void
    {
        $supplier = $this->supplier();

        $this->actingAs($supplier)->post(route('supplier.email.code'));
        $this->actingAs($supplier)->post(route('supplier.email.verify'), ['code' => '000000'])
            ->assertSessionHasErrors(['code' => __('That code is wrong or has expired.')]);

        $this->assertFalse($supplier->fresh()->hasVerifiedEmail());
    }

    public function test_a_verified_email_gets_no_new_code(): void
    {
        $supplier = User::factory()->create()->assignRole(OwnerRegistrationService::ROLE);

        $this->actingAs($supplier)->post(route('supplier.email.code'))
            ->assertSessionHasErrors(['database' => __('Your email address is already verified.')]);
        Notification::assertNothingSent();
    }

    public function test_sending_email_codes_is_throttled(): void
    {
        $supplier = $this->supplier();

        foreach (range(1, 3) as $attempt) {
            $this->actingAs($supplier)->post(route('supplier.email.code'))->assertSessionHasNoErrors();
        }

        $this->actingAs($supplier)->post(route('supplier.email.code'))->assertSessionHasErrors('database');
    }

    public function test_the_dashboard_and_profile_show_an_unverified_email(): void
    {
        $supplier = $this->supplier();

        $this->actingAs($supplier)->get(route('supplier.dashboard'))
            ->assertInertia(fn ($page) => $page->where('unverified_email', true));
        $this->actingAs($supplier)->get(route('supplier.profile.show'))
            ->assertInertia(fn ($page) => $page->where('profile.email_verified', false));
    }

    private function supplier(): User
    {
        return User::factory()->unverified()->create()->assignRole(OwnerRegistrationService::ROLE);
    }
}
