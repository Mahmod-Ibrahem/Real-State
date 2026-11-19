<?php

namespace Modules\Property\Http\Resources\Users;

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
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => str_replace('&nbsp;', ' ', strip_tags($this->description)),
            'sqft' => $this->sqft,
            'floor' => $this->floor,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'price' => $this->price,
            'image_url' => $this->image,
            'status' => $this->status->badge(),
            'google_map' => $this->google_map,
            'propertyImages' => $this->propertyImages,
            'features' => $this->features->map(fn($feature) => [
                'name' => $feature->name
            ]),
        ];
    }
}
