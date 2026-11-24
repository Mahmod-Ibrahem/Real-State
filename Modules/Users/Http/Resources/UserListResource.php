<?php

namespace Modules\Users\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $role = $this->roles->first();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => $role?->id,
            'role_name' => $role?->name,
            'is_active' => $this->is_active,
            'under_review' => $this->under_review,
            'properties_count' => $this->properties_count,
            'can_be_impersonated' => $this->canBeImpersonated() && ! $this->resource->is($request->user()),
            'created_at' => $this->created_at,
        ];
    }
}
