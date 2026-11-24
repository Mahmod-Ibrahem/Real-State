<?php

namespace App\Exceptions;

final class ActionBlockedWhileImpersonating extends BusinessRuleException
{
    public static function make(): self
    {
        return new self(__("You can't do this while logged in as another user."));
    }
}
