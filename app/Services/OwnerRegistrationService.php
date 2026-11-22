<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;

final class OwnerRegistrationService
{
    public const ROLE = 'Property Owner';

    /** @param array{name: string, email: string, password: string} $data */
    public function register(array $data): User
    {
        $user = User::create($data);
        $user->assignRole(self::ROLE);

        event(new Registered($user));

        return $user;
    }
}
