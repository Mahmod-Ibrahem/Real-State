<?php

namespace Modules\Property\Http\Resources\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyListResource extends JsonResource
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
        'sqft' => $this->sqft,
        'bedrooms' => $this->bedrooms,
        'bathrooms' => $this->bathrooms,
        'price' => $this->price,
        'image_url' => $this->image,
        'status' => $this->status->badge(),
        'finishing' => ['id' => $this->finishing->value, 'name' => $this->finishing->label()],
        'delivery' => ['ready' => $this->isReady(), 'year' => $this->isReady() ? null : $this->delivery_year],
        'owner' => OwnerResource::make($this->whenLoaded('owner')),
    ];
    }
}
