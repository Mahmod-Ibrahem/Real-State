<?php

namespace App\Exceptions;

final class PhoneCodeNotSent extends BusinessRuleException
{
    public static function make(): self
    {
        return new self(__("We couldn't send a code to this number. Please try again later."));
    }
}
