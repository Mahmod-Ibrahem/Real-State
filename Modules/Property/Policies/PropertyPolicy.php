<?php

namespace Modules\Property\Policies;

use App\Models\User;
use Modules\Property\Models\Property;

final class PropertyPolicy
{
    public function update(User $user, Property $property): bool
    {
        return $property->owner()->is($user);
    }

    public function publish(User $user, Property $property): bool
    {
        return $this->update($user, $property);
    }
}
