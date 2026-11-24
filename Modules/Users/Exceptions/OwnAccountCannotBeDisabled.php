<?php

namespace Modules\Users\Exceptions;

use App\Exceptions\BusinessRuleException;

final class OwnAccountCannotBeDisabled extends BusinessRuleException
{
    public static function make(): self
    {
        return new self(__("You can't disable your own account."));
    }
}
