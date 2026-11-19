<?php

namespace Modules\Property\Services;

use Modules\Location\Http\Resources\AreaResource;
use Modules\Location\Http\Resources\CityResource;
use Modules\Location\Http\Resources\CountryResource;
use Modules\Location\Services\AreaService;
use Modules\Location\Services\CityService;
use Modules\Location\Services\CountryService;
use Modules\Property\Enums\ListingPurpose;
use Modules\Property\Http\Resources\PropertyClassificationeResource;
use Modules\Property\Http\Resources\PropertyTypeResource;

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
