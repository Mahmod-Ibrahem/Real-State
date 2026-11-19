<?php

namespace App\Observers;

use App\Models\PropertyClassifications;
use Illuminate\Support\Facades\Cache;

class PropertyClassificationObserver
{
    /**
     * Handle the PropertyClassifications "created" event.
     */
    public function created(PropertyClassifications $propertyClassifications): void
    {
        Cache::forget('classifications');
    }

    /**
     * Handle the PropertyClassifications "updated" event.
     */
    public function updated(PropertyClassifications $propertyClassifications): void
    {
        Cache::forget('classifications');
    }

    /**
     * Handle the PropertyClassifications "deleted" event.
     */
    public function deleted(PropertyClassifications $propertyClassifications): void
    {
        Cache::forget('classifications');
    }

    /**
     * Handle the PropertyClassifications "restored" event.
     */
    public function restored(PropertyClassifications $propertyClassifications): void
    {
        Cache::forget('classifications');
    }

    /**
     * Handle the PropertyClassifications "force deleted" event.
     */
    public function forceDeleted(PropertyClassifications $propertyClassifications): void
    {
        Cache::forget('classifications');
    }
}
