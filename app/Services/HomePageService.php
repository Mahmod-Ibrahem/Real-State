<?php

namespace App\Services;

use App\Enums\ListingPurpose;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\{AreaResource,
    CityResource,
    CountryResource,
    Users\PropertyListResource,
    Users\PropertyTypeResource
};
use App\Models\{BasicContent, PropertyClassifications};

class HomePageService
{
    public function __construct(
        protected CityService           $cityService,
        protected PropertyTypeService   $propertyTypeService,
        protected AreaService           $areaService,
        protected CountryService        $countryService,
        protected BrokerService         $brokerService,
    )
    {
    }

    public
    function getHomePageData(): array
    {
        return [
            'homeVideo' => $this->homeVideo(),
            'propertyTypes' => PropertyTypeResource::collection($this->propertyTypeService->all()),
            'classifications' => $this->propertiesClassification(),
            'purposes' => ListingPurpose::options(),
            'brokers' => $this->brokerService->all(),
            'cities' => CityResource::collection($this->cityService->all()),
            'areas' => AreaResource::collection($this->areaService->all()),
            'countries' => CountryResource::collection($this->countryService->all()),
        ];
    }

    protected
    function homeVideo()
    {
        return Cache::rememberForever('home_video', fn() => BasicContent::where('type', 'home_video')->first()?->only('content') ?? '');
    }

    protected function propertiesClassification()
    {
        return Cache::rememberForever('property_classifications', function () {
            return PropertyClassifications::whereHas('properties', fn($q) => $q->active())
                ->whereIn('name->en', ['Affordable', 'Luxury'])
                ->with('properties')
                ->get()
                ->map(fn($class) => [
                    'id' => $class->id,
                    'name' => $class->name,
                    'properties' => PropertyListResource::collection($class->properties)
                ]);
        });
    }

}
