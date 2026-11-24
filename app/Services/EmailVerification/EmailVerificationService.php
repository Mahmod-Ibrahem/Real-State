<?php

namespace App\Services\EmailVerification;

use App\Exceptions\EmailAlreadyVerified;
use App\Models\User;
use Illuminate\Validation\ValidationException;

final class EmailVerificationService
{
    public function __construct(private readonly EmailVerifier $verifier)
    {
    }

    public function sendCode(User $user): void
    {
        $this->ensureNotVerified($user);

        $this->verifier->send($user->email);
    }

    public function confirm(User $user, string $code): void
    {
        $this->ensureNotVerified($user);

        if (! $this->verifier->check($user->email, $code)) {
            throw ValidationException::withMessages(['code' => __('That code is wrong or has expired.')]);
        }

        $user->markEmailAsVerified();
    }

    private function ensureNotVerified(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            throw EmailAlreadyVerified::make();
        }
    }
}
