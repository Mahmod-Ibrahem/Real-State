<?php

namespace Modules\Users\Exceptions;

use App\Exceptions\BusinessRuleException;

final class OwnRoleCannotChange extends BusinessRuleException
{
    public static function make(): self
    {
        return new self(__("You can't change your own role."));
    }
}
