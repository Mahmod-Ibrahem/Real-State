<?php

namespace Modules\Property\Observers;

use Modules\Property\Models\PropertyType;
use Illuminate\Support\Facades\Cache;

class PropertyTypeObserver
{
    /**
     * Handle the PropertyType "created" event.
     */
    public function created(PropertyType $propertyType): void
    {
        Cache::forget('property_types');
    }

    /**
     * Handle the PropertyType "updated" event.
     */
    public function updated(PropertyType $propertyType): void
    {
        Cache::forget('property_types');

    }

    /**
     * Handle the PropertyType "deleted" event.
     */
    public function deleted(PropertyType $propertyType): void
    {
        Cache::forget('property_types');
    }

    /**
     * Handle the PropertyType "restored" event.
     */
    public function restored(PropertyType $propertyType): void
    {
        Cache::forget('property_types');
    }

    /**
     * Handle the PropertyType "force deleted" event.
     */
    public function forceDeleted(PropertyType $propertyType): void
    {
        Cache::forget('property_types');
    }
}
