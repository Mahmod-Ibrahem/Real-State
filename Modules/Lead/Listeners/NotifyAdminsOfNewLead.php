<?php

namespace Modules\Lead\Listeners;

use App\Enums\NotificationPermission;
use App\Models\User;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Notification;
use Modules\Lead\Events\PropertyLeadReceived;
use Modules\Lead\Notifications\NewPropertyLeadNotification;

final class NotifyAdminsOfNewLead implements ShouldHandleEventsAfterCommit
{
    public function handle(PropertyLeadReceived $event): void
    {
        Notification::send(
            User::permission(NotificationPermission::PropertyLeads->value)->whereKeyNot($event->lead->owner_id)->get(),
            new NewPropertyLeadNotification($event->lead, route('admin.property-leads.index')),
        );
    }
}
