<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ImagesUtility;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

final class ProfileService
{
    use ImagesUtility;

    private const AVATAR_TYPE = 'avatar';

    /**
     * Saves the user's profile columns (filtered by `$fillable`), plus an optional new `avatar` photo or
     * `remove_avatar`. Changing the email marks it unverified.
     */
    public function update(User $user, array $data): void
    {
        $previousAvatar = $user->avatar;

        $user->fill($data);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if (isset($data['avatar'])) {
            $user->avatar = $this->storeImage($data['avatar'], self::AVATAR_TYPE);
        } elseif ($data['remove_avatar'] ?? false) {
            $user->avatar = null;
        }

        $user->save();

        if ($user->wasChanged('avatar')) {
            $this->deleteUploadedAvatar($previousAvatar);
        }
    }

    /** Only uploaded avatars are deleted; seeded ones point at shared demo images. */
    private function deleteUploadedAvatar(?string $url): void
    {
        if ($url !== null && Str::startsWith($url, url('storage/' . $this->generatePath(self::AVATAR_TYPE)))) {
            File::delete(storage_path('app/public' . $this->getRelativePath($url)));
        }
    }
}
