<?php

namespace Modules\Lead\Exceptions;

use App\Exceptions\BusinessRuleException;

final class OwnerNotReachable extends BusinessRuleException
{
    public static function make(): self
    {
        return new self(__('This owner can\'t be reached by phone. Send them a message instead.'));
    }
}
