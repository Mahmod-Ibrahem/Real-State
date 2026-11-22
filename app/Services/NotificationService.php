<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;

final class NotificationService
{
    private const LATEST = 10;

    public function latestFor(User $user): Collection
    {
        return $user->notifications()->latest()->take(self::LATEST)->get();
    }

    public function unreadCountFor(User $user): int
    {
        return $user->unreadNotifications()->count();
    }

    public function open(User $user, string $notificationId): DatabaseNotification
    {
        $notification = $user->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        return $notification;
    }

    public function markAllRead(User $user): void
    {
        $user->unreadNotifications()->update(['read_at' => now()]);
    }
}
