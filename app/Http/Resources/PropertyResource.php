<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public static $wrap = false;
    public function toArray(Request $request): array
    {
        $locale=\request('locale') ?? 'en';
        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', $locale, $this->name),
            'description' => $this->getTranslation('description', $locale, $this->description),
            'price' => $this->price,
            'image' => $this->image,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'garages' => $this->garages,
            'address' => $this->getTranslation('address', $locale, $this->address),
            'year_built' => $this->year_built,
            'sqft' => $this->sqft,
            'floor' => $this->floor,
            'google_map' => $this->google_map,
            'is_published' => $this->is_published,
            'featureIds' => $this->features->pluck('id'),
            'propertyTypeId' => $this->propertyType->id,
            'classificationId' => $this->propertyClassification->id,
            'purpose' => $this->purpose->value,
            'status' => $this->status->value,
            'cityId' => $this->city->id,
            'areaId' => $this->area->id,
            'countryId' => $this->country?->id,
            ];
    }
}
