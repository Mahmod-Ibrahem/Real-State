<?php

namespace Modules\Property\Listeners;

use App\Enums\NotificationPermission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Property\Events\PropertyApproved;
use Modules\Property\Notifications\PropertyApprovedNotification;

final class NotifyOwnerOfApprovedProperty implements ShouldQueue
{
    public bool $afterCommit = true;

    public function handle(PropertyApproved $event): void
    {
        $owner = $event->property->owner;

        if ($owner->can(NotificationPermission::Receive->value)) {
            $owner->notify(new PropertyApprovedNotification($event->property));
        }
    }
}
