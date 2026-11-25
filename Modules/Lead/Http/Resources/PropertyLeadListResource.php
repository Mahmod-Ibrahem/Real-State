<?php

namespace Modules\Lead\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyLeadListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
            'source' => $this->source->value,
            'source_label' => $this->source->label(),
            'status' => $this->status->value,
            'property_id' => $this->property_id,
            'property_name' => $this->whenLoaded('property', fn () => $this->property?->name),
            'owner_name' => $this->whenLoaded('owner', fn () => $this->owner?->name),
            'created_at' => $this->created_at,
        ];
    }
}
