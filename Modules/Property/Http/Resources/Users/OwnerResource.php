<?php

namespace Modules\Property\Http\Resources\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** A property owner as the public site shows them: never their email or numbers. */
class OwnerResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'verified' => $this->hasVerifiedPhone(),
            'member_since' => $this->created_at?->year,
            'listed_properties_count' => $this->whenCounted('listed_properties'),
        ];
    }
}
