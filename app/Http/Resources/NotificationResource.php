<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'message' => $this->data['message'] ?? '',
            'url' => $this->data['url'] ?? null,
            'is_read' => $this->read_at !== null,
            'created_at' => $this->created_at,
        ];
    }
}
