<?php

namespace App\Services;

use App\Exceptions\EmailAlreadyVerified;
use App\Models\User;
use App\Notifications\EmailVerificationCodeNotification;
use Illuminate\Validation\ValidationException;

final class EmailVerificationService
{
    public function __construct(private readonly VerificationCodes $codes)
    {
    }

    public function sendCode(User $user): void
    {
        $this->ensureNotVerified($user);

        $user->notify(new EmailVerificationCodeNotification($this->codes->issue($this->key($user))));
    }

    public function confirm(User $user, string $code): void
    {
        $this->ensureNotVerified($user);

        if (! $this->codes->check($this->key($user), $code)) {
            throw ValidationException::withMessages(['code' => __('That code is wrong or has expired.')]);
        }

        $user->markEmailAsVerified();
    }

    private function key(User $user): string
    {
        return "email:{$user->email}";
    }

    private function ensureNotVerified(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            throw EmailAlreadyVerified::make();
        }
    }
}
