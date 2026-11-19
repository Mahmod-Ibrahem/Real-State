<?php

namespace App\Observers;

use App\Models\Area;
use Illuminate\Support\Facades\Cache;

class AreaObserver
{
    /**
     * Handle the Area "created" event.
     */
    public function created(Area $area): void
    {
        Cache::forget('areas');
    }

    /**
     * Handle the Area "updated" event.
     */
    public function updated(Area $area): void
    {
        Cache::forget('areas');

    }

    /**
     * Handle the Area "deleted" event.
     */
    public function deleted(Area $area): void
    {
        Cache::forget('areas');
    }

    /**
     * Handle the Area "restored" event.
     */
    public function restored(Area $area): void
    {
        Cache::forget('areas');
    }

    /**
     * Handle the Area "force deleted" event.
     */
    public function forceDeleted(Area $area): void
    {
        Cache::forget('areas');
    }
}
