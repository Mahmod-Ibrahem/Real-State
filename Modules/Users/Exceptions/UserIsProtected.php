<?php

namespace Modules\Users\Exceptions;

use App\Exceptions\BusinessRuleException;

final class UserIsProtected extends BusinessRuleException
{
    public static function make(): self
    {
        return new self(__("This account is protected and can't be changed here."));
    }
}
