<?php

namespace Modules\Property\Http\Resources\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerPropertyListResource extends JsonResource
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
            'price' => $this->price,
            'review_status' => $this->review_status->value,
            'rejection_reason' => $this->rejection_reason,
            'is_published' => $this->is_published,
        ];
    }
}
