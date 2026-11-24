<?php

namespace App\Exceptions;

final class CannotImpersonate extends BusinessRuleException
{
    public static function self(): self
    {
        return new self(__("You can't log in as yourself."));
    }

    public static function protectedAccount(): self
    {
        return new self(__('Only supplier accounts can be logged into.'));
    }

    public static function inactive(): self
    {
        return new self(__('This account is disabled.'));
    }

    public static function alreadyImpersonating(): self
    {
        return new self(__('Return to your own account first.'));
    }

    public static function notImpersonating(): self
    {
        return new self(__("You aren't logged in as another user."));
    }
}
