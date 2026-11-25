<?php

namespace Modules\Lead\Policies;

use App\Models\User;
use Modules\Lead\Models\PropertyLead;

final class PropertyLeadPolicy
{
    public function update(User $user, PropertyLead $lead): bool
    {
        return $lead->owner()->is($user);
    }
}
