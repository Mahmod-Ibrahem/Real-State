<?php

namespace Modules\Property\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Property\Http\Resources\Users\PropertyResource;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyType;
use Modules\Property\Services\ListingFilterService;
use Modules\Property\Services\PropertyTypeService;
use Inertia\Inertia;

class PropertyTypeController extends Controller
{
    public function __construct(protected ListingFilterService $listingFilterService, protected PropertyTypeService $propertyTypeService)
    {
    }

    public function show(string $PropertyType)
    {
        $propertyType = PropertyType::where('slug->' . app()->getLocale(), $PropertyType)
            ->orWhere('slug->en', $PropertyType)
            ->first();
        $properties = Property::where('property_type_id', $propertyType->id)->paginate(10);
        return Inertia::render('Users/Listing',
            array_merge([
                'properties' => PropertyResource::collection($properties),
            ], $this->listingFilterService->getFilters()));
    }

}
