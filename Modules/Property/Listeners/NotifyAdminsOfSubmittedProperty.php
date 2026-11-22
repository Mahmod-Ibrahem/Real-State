<?php

namespace Modules\Property\Listeners;

use App\Enums\NotificationPermission;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Modules\Property\Events\PropertySubmitted;
use Modules\Property\Notifications\PropertySubmittedNotification;

final class NotifyAdminsOfSubmittedProperty implements ShouldQueue
{
    public bool $afterCommit = true;

    public function handle(PropertySubmitted $event): void
    {
        Notification::send(
            User::permission(NotificationPermission::PropertySubmissions->value)->get(),
            new PropertySubmittedNotification($event->property),
        );
    }
}
