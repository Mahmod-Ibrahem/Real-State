<?php

namespace App\Providers;

use App\Services\EmailVerification\EmailVerifier;
use App\Services\EmailVerification\LogEmailVerifier;
use App\Services\EmailVerification\MailEmailVerifier;
use App\Services\PhoneVerification\LogPhoneVerifier;
use App\Services\PhoneVerification\PhoneVerifier;
use App\Services\PhoneVerification\TwilioPhoneVerifier;
use App\Services\PhoneVerification\WhatsAppPhoneVerifier;
use App\Services\VerificationCodes;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Twilio\Rest\Client;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PhoneVerifier::class, fn ($app) => match ($driver = config('services.phone_verification.driver')) {
            'twilio' => new TwilioPhoneVerifier(
                new Client(config('services.twilio.sid'), config('services.twilio.token')),
                config('services.twilio.verify_sid'),
            ),
            'whatsapp' => new WhatsAppPhoneVerifier(
                $app->make(VerificationCodes::class),
                (string) config('services.whatsapp.token'),
                (string) config('services.whatsapp.phone_number_id'),
                config('services.whatsapp.otp_template'),
                config('services.whatsapp.template_language'),
                config('services.whatsapp.graph_version'),
            ),
            'log' => $app->make(LogPhoneVerifier::class),
            default => throw new InvalidArgumentException("Unknown phone verification driver [{$driver}]."),
        });

        $this->app->bind(EmailVerifier::class, fn ($app) => match ($driver = config('services.email_verification.driver')) {
            'mail' => $app->make(MailEmailVerifier::class),
            'log' => $app->make(LogEmailVerifier::class),
            default => throw new InvalidArgumentException("Unknown email verification driver [{$driver}]."),
        });
    }

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
