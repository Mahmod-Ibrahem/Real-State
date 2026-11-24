<?php

namespace App\Exceptions;

final class EmailCodeNotSent extends BusinessRuleException
{
    public static function make(): self
    {
        return new self(__("We couldn't send a code to this email address. Please try again later."));
    }
}
