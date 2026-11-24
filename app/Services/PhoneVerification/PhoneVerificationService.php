<?php

namespace App\Services\PhoneVerification;

use App\Exceptions\PhoneAlreadyVerified;
use App\Models\User;
use Illuminate\Validation\ValidationException;

final class PhoneVerificationService
{
    public function __construct(private readonly PhoneVerifier $verifier)
    {
    }

    public function sendCode(User $user): void
    {
        $this->ensureNotVerified($user);

        $this->verifier->send($user->phone);
    }

    public function confirm(User $user, string $code): void
    {
        $this->ensureNotVerified($user);

        if (! $this->verifier->check($user->phone, $code)) {
            throw ValidationException::withMessages(['code' => __('That code is wrong or has expired.')]);
        }

        $user->forceFill(['phone_verified_at' => now()])->save();
    }

    private function ensureNotVerified(User $user): void
    {
        if ($user->hasVerifiedPhone()) {
            throw PhoneAlreadyVerified::make();
        }
    }
}
