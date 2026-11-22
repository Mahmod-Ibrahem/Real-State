<?php

namespace Modules\Property\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationOptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country_id' => $this->whenHas('country_id'),
            'city_id' => $this->whenHas('city_id'),
        ];
    }
}
