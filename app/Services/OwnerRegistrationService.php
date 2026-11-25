<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\UploadedFile;

final class OwnerRegistrationService
{
    public const ROLE = 'Property Owner';

    public function __construct(private readonly ProfileService $profiles)
    {
    }

    /** @param array{name: string, email: string, phone: string, phone_country: string, password: string, avatar?: ?UploadedFile} $data */
    public function register(array $data): User
    {
        $user = User::create([...$data, 'under_review' => true]);
        $user->assignRole(self::ROLE);

        if (isset($data['avatar'])) {
            $this->profiles->update($user, ['avatar' => $data['avatar']]);
        }

        event(new Registered($user));

        return $user;
    }
}
