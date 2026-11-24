<?php

namespace App\Services\EmailVerification;

use App\Services\VerificationCodes;
use Illuminate\Support\Facades\Log;

final class LogEmailVerifier implements EmailVerifier
{
    public function __construct(private readonly VerificationCodes $codes)
    {
    }

    public function send(string $email): void
    {
        $code = $this->codes->issue("email:{$email}");

        Log::info("Email verification code for {$email}: {$code}");
    }

    public function check(string $email, string $code): bool
    {
        return $this->codes->check("email:{$email}", $code);
    }
}
