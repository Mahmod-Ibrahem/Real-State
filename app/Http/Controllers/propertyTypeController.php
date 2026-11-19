<?php

namespace App\Http\Controllers;

use App\Http\Resources\Users\propertyResource;
use App\Models\Property;
use App\Models\PropertyType;
use App\Services\ListingFilterService;
use App\Services\PropertyTypeService;
use Inertia\Inertia;

class propertyTypeController extends Controller
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
                'properties' => propertyResource::collection($properties),
            ], $this->listingFilterService->getFilters()));
    }

}
