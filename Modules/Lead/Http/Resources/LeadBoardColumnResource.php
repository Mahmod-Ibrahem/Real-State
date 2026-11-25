<?php

namespace Modules\Lead\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** One Kanban column: ['status' => LeadStatus, 'count' => int, 'leads' => Collection] */
class LeadBoardColumnResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource['status']->value,
            'name' => $this->resource['status']->label(),
            'tone' => $this->resource['status']->tone(),
            'count' => $this->resource['count'],
            'items' => PropertyLeadListResource::collection($this->resource['leads'])->resolve($request),
        ];
    }
}
