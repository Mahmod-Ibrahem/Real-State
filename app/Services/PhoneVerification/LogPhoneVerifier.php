<?php

namespace App\Services\PhoneVerification;

use App\Services\VerificationCodes;
use Illuminate\Support\Facades\Log;

final class LogPhoneVerifier implements PhoneVerifier
{
    public function __construct(private readonly VerificationCodes $codes)
    {
    }

    public function send(string $phone): void
    {
        $code = $this->codes->issue("phone:{$phone}");

        Log::info("Phone verification code for {$phone}: {$code}");
    }

    public function check(string $phone, string $code): bool
    {
        return $this->codes->check("phone:{$phone}", $code);
    }
}
