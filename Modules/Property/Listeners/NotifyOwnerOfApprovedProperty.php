<?php

namespace Modules\Property\Listeners;

use App\Enums\NotificationPermission;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Modules\Property\Events\PropertyApproved;
use Modules\Property\Notifications\PropertyApprovedNotification;

final class NotifyOwnerOfApprovedProperty implements ShouldHandleEventsAfterCommit
{
    public function handle(PropertyApproved $event): void
    {
        $owner = $event->property->owner;

        if ($owner->can(NotificationPermission::Receive->value)) {
            $owner->notify(new PropertyApprovedNotification($event->property));
        }
    }
}
