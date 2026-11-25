<?php

namespace Modules\Property\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Property\Enums\InstallmentFrequency;
use Modules\Property\Enums\ListingPurpose;
use Modules\Property\Enums\PropertyStatus;

class PropertyFormOptionsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'property_types' => PropertyTypeResource::collection($this->resource['property_types']),
            'classifications' => PropertyClassificationeResource::collection($this->resource['classifications']),
            'features' => FeatureResource::collection($this->resource['features']),
            'countries' => LocationOptionResource::collection($this->resource['countries']),
            'cities' => LocationOptionResource::collection($this->resource['cities']),
            'areas' => LocationOptionResource::collection($this->resource['areas']),
            'purposes' => ListingPurpose::options(),
            'statuses' => PropertyStatus::options(),
            'installment_frequencies' => InstallmentFrequency::options(),
        ];
    }
}
