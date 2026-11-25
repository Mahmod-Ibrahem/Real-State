<?php

namespace Modules\Property\Exceptions;

use App\Exceptions\BusinessRuleException;

final class PropertyHasLeads extends BusinessRuleException
{
    public static function make(): self
    {
        return new self(__('This property still has leads.'));
    }
}
