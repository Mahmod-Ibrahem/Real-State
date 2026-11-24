<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class LoginService
{
    /** @param array{email: string, password: string} $credentials */
    public function attempt(array $credentials, bool $remember): User
    {
        if (! Auth::validate($credentials)) {
            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }

        /** @var User $user */
        $user = Auth::getLastAttempted();

        if (! $user->is_active) {
            throw ValidationException::withMessages(['email' => __('Your account is disabled.')]);
        }

        Auth::login($user, $remember);

        return $user;
    }
}
