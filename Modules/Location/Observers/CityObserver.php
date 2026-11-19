<?php

namespace Modules\Location\Observers;

use Modules\Location\Models\City;
use Illuminate\Support\Facades\Cache;

class CityObserver
{
    /**
     * Handle the City "created" event.
     */
    public function created(City $city): void
    {
        Cache::forget('cities');
    }

    /**
     * Handle the City "updated" event.
     */
    public function updated(City $city): void
    {
        Cache::forget('cities');

    }

    /**
     * Handle the City "deleted" event.
     */
    public function deleted(City $city): void
    {
        Cache::forget('cities');
    }

    /**
     * Handle the City "restored" event.
     */
    public function restored(City $city): void
    {
        Cache::forget('cities');
    }

    /**
     * Handle the City "force deleted" event.
     */
    public function forceDeleted(City $city): void
    {
        Cache::forget('cities');

    }
}
