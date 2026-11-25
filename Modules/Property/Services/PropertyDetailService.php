<?php

namespace Modules\Property\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;
use Modules\Property\Models\Feature;
use Modules\Property\Models\Property;

final class PropertyDetailService
{
    private const RELATED_LIMIT = 6;

    public function findListed(string $slug): Property
    {
        return Property::query()
            ->active()
            ->whereJsonContainsLocales('slug', [app()->getLocale(), config('app.fallback_locale')], $slug)
            ->with(['propertyImages', 'owner:id,phone'])
            ->firstOrFail();
    }

    public function relatedTo(Property $property): Collection
    {
        return Property::query()
            ->active()
            ->where('property_type_id', $property->property_type_id)
            ->whereKeyNot($property->getKey())
            ->with('propertyImages')
            ->limit(self::RELATED_LIMIT)
            ->get();
    }

    /** @return BaseCollection<string, Collection<int, Feature>> */
    public function featuresByType(Property $property): BaseCollection
    {
        return $property->features()
            ->whereHas('featureType')
            ->with('featureType')
            ->get()
            ->groupBy('featureType.name');
    }
}
