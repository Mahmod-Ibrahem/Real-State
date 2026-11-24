<?php

namespace App\Providers;

use App\Services\PhoneVerification\LogPhoneVerifier;
use App\Services\PhoneVerification\PhoneVerifier;
use App\Services\PhoneVerification\TwilioPhoneVerifier;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Twilio\Rest\Client;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PhoneVerifier::class, fn () => match ($driver = config('services.phone_verification.driver')) {
            'twilio' => new TwilioPhoneVerifier(
                new Client(config('services.twilio.sid'), config('services.twilio.token')),
                config('services.twilio.verify_sid'),
            ),
            'log' => new LogPhoneVerifier(),
            default => throw new InvalidArgumentException("Unknown phone verification driver [{$driver}]."),
        });
    }

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
