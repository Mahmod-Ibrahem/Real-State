<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerProfileResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'email_verified' => $this->hasVerifiedEmail(),
            'phone' => $this->phone,
            'phone_verified' => $this->hasVerifiedPhone(),
            'whatsapp_phone' => $this->whatsapp_phone,
            'whatsapp_country' => $this->whatsapp_country,
            'avatar' => $this->avatar,
        ];
    }
}
