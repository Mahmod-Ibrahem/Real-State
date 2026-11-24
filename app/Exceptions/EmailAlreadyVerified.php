<?php

namespace App\Exceptions;

final class EmailAlreadyVerified extends BusinessRuleException
{
    public static function make(): self
    {
        return new self(__('Your email address is already verified.'));
    }
}
