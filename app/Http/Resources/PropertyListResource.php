<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'propertyType' => $this->propertyType->name,
            'city' => $this->city->name,
            'area' => $this->area->name,
            // Raw values for the table's quick edits; labels come from the purposes/statuses options
            'purpose' => $this->purpose->value,
            'status' => $this->status->value,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'garages' => $this->garages,
            'sqft' => $this->sqft,
            'price' => $this->price,
            'image_url' => $this->image,
            'is_published' => (bool) $this->is_published,
        ];
    }
}
