<?php

namespace App\Services;

use App\Enums\ListingPurpose;
use App\Http\Resources\{
    CityResource,
    PropertyTypeResource,
    PropertyClassificationeResource,
    AreaResource,
    CountryResource
};

class ListingFilterService
{
    public function __construct(
        protected CityService           $cityService,
        protected PropertyTypeService   $propertyTypeService,
        protected ClassificationService $classificationService,
        protected AreaService           $areaService,
        protected CountryService        $countryService,
    )
    {
    }

    public function getFilters(): array
    {
        return [
            'cities' => CityResource::collection($this->cityService->all()),
            'propertyType' => PropertyTypeResource::collection($this->propertyTypeService->all()),
            'purposes' => ListingPurpose::options(),
            'classifications' => PropertyClassificationeResource::collection($this->classificationService->all()),
            'areas' => AreaResource::collection($this->areaService->all()),
            'countries' => CountryResource::collection($this->countryService->all()),
        ];
    }
}
