<?php

namespace Modules\Property\Exceptions;

use App\Exceptions\BusinessRuleException;
use Modules\Property\Models\Property;

final class PropertyAlreadyApproved extends BusinessRuleException
{
    public static function for(Property $property): self
    {
        return new self(__(':name is already approved.', ['name' => $property->name]));
    }
}
