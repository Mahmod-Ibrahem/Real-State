<?php

namespace Modules\Property\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

final class OwnerObserver
{
    private const PUBLIC_LIST_KEYS = ['property_types', 'classifications', 'property_classifications', 'cities', 'areas'];
    /** under_review hides or shows their properties; the rest is shown on the cached home page cards. */
    private const PUBLIC_COLUMNS = ['under_review', 'name', 'avatar', 'phone_verified_at'];

    public function updated(User $owner): void
    {
        if (! $owner->wasChanged(self::PUBLIC_COLUMNS)) {
            return;
        }

        foreach (self::PUBLIC_LIST_KEYS as $key) {
            Cache::forget($key);
        }
    }
}
