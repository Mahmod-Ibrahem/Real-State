<?php

namespace Modules\Property\Services;

use Modules\Location\Models\City;
use Modules\Property\Models\PropertyType;
use Illuminate\Support\Facades\Cache;

class PropertyTypeService
{
    public function all()
    {
        return Cache::rememberForever('property_types', function () {
            return PropertyType::whereHas('properties', fn($q) => $q->active())
                ->with(['properties' => fn($q) => $q->active()])
                ->withCount(['properties' => fn($q) => $q->active()])
                ->get();
        });
    }
}
