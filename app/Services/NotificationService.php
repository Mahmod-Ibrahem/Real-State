<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

final class NotificationService
{
    private const LATEST = 10;

    public function latestFor(User $user): Collection
    {
        return $user->notifications()->latest()->take(self::LATEST)->get();
    }

    public function markAllRead(User $user): void
    {
        $user->unreadNotifications()->update(['read_at' => now()]);
    }
}
