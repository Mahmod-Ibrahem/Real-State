<?php

namespace Modules\Property\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Property\Models\Property;

final class PropertySubmitted
{
    use Dispatchable, SerializesModels;

    public function __construct(public Property $property)
    {
    }
}
