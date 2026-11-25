<?php

namespace Modules\Lead\Listeners;

use App\Enums\NotificationPermission;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Modules\Lead\Events\PropertyLeadReceived;
use Modules\Lead\Notifications\NewPropertyLeadNotification;

final class NotifyOwnerOfNewLead implements ShouldHandleEventsAfterCommit
{
    public function handle(PropertyLeadReceived $event): void
    {
        $owner = $event->lead->owner;

        if (! $owner->can(NotificationPermission::Receive->value)) {
            return;
        }

        $owner->notify(new NewPropertyLeadNotification($event->lead, route('supplier.leads.index')));
    }
}
