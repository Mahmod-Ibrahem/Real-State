<?php

namespace Modules\Property\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'property_type' => $this->propertyType->name,
            'city' => $this->city->name,
            'area' => $this->area->name,
            'owner' => $this->owner->name,
            'purpose' => $this->purpose->value,
            'status' => $this->status->value,
            'review_status' => $this->review_status->value,
            'rejection_reason' => $this->rejection_reason,
            'is_published' => $this->is_published,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'sqft' => $this->sqft,
            'price' => $this->price,
        ];
    }
}
