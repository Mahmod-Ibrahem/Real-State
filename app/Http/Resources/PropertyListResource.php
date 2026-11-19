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
            'purpose' => $this->purpose->label(),
            'status' => $this->status->label(),
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'garages' => $this->garages,
            'sqft' => $this->sqft,
            'price' => $this->price,
            'image_url' => $this->image,
            'is_published' => $this->is_published  ? 'Published' : 'Unpublished',
        ];
    }
}
