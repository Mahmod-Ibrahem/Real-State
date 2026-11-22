<?php

namespace Modules\Property\Exceptions;

use App\Exceptions\BusinessRuleException;

final class PropertyNotApproved extends BusinessRuleException
{
    public static function cannotPublish(): self
    {
        return new self(__('Only approved properties can be published.'));
    }
}
