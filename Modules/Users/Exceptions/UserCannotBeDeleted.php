<?php

namespace Modules\Users\Exceptions;

use App\Exceptions\BusinessRuleException;

final class UserCannotBeDeleted extends BusinessRuleException
{
    public static function self(): self
    {
        return new self(__("You can't delete your own account."));
    }

    public static function hasProperties(): self
    {
        return new self(__('This user still has properties. Move or delete them first.'));
    }
}
