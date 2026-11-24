<?php

namespace App\Exceptions;

final class PhoneAlreadyVerified extends BusinessRuleException
{
    public static function make(): self
    {
        return new self(__('Your phone number is already verified.'));
    }
}
