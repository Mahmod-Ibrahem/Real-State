<?php

namespace Modules\Property\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

final class OwnerObserver
{
    private const PUBLIC_LIST_KEYS = ['property_types', 'classifications', 'property_classifications', 'cities', 'areas'];

    public function updated(User $owner): void
    {
        if (! $owner->wasChanged('under_review')) {
            return;
        }

        foreach (self::PUBLIC_LIST_KEYS as $key) {
            Cache::forget($key);
        }
    }
}
