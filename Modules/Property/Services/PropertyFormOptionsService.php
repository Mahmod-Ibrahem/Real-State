<?php

namespace Modules\Property\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Location\Models\Area;
use Modules\Location\Models\City;
use Modules\Location\Models\Country;
use Modules\Property\Models\Feature;
use Modules\Property\Models\PropertyClassifications;
use Modules\Property\Models\PropertyType;

final class PropertyFormOptionsService
{
    /** @return array<string, Collection> */
    public function all(): array
    {
        return [
            'property_types' => PropertyType::all(),
            'classifications' => PropertyClassifications::all(),
            'features' => Feature::with('featureType')->get(),
            'countries' => Country::all(),
            'cities' => City::all(),
            'areas' => Area::all(),
        ];
    }
}
