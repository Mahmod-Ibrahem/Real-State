<?php

namespace Modules\Property\Observers;

use Modules\Property\Models\Property;
use Illuminate\Support\Facades\Cache;

class PropertyObserver
{
    /**
     * Handle the Property "created" event.
     */
    public function created(Property $property): void
    {
        Cache::forget('property_types');
        Cache::forget('classifications');
        Cache::forget('cities');
        Cache::forget('areas');
        Cache::forget("property.{$property->slug}");
    }

    /**
     * Handle the Property "updated" event.
     */
    public function updated(Property $property): void
    {
        Cache::forget('property_types');
        Cache::forget('classifications');
        Cache::forget('cities');
        Cache::forget('areas');
        Cache::forget("property.{$property->slug}");

    }

    /**
     * Handle the Property "deleted" event.
     */
    public function deleted(Property $property): void
    {
        Cache::forget('property_types');
        Cache::forget('classifications');
        Cache::forget('cities');
        Cache::forget('areas');
        Cache::forget("property.{$property->slug}");
    }

    /**
     * Handle the Property "restored" event.
     */
    public function restored(Property $property): void
    {
        Cache::forget('property_types');
        Cache::forget('classifications');
        Cache::forget('cities');
        Cache::forget('areas');
        Cache::forget("property.{$property->slug}");
    }

    /**
     * Handle the Property "force deleted" event.
     */
    public function forceDeleted(Property $property): void
    {
        Cache::forget('property_types');
        Cache::forget('classifications');
        Cache::forget('cities');
        Cache::forget('areas');
    }
}
