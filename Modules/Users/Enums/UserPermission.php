<?php

namespace Modules\Users\Enums;

use App\Enums\Concerns\IsPermission;

enum UserPermission: string
{
    use IsPermission;

    case View = 'view users';
    case Create = 'create users';
    case Edit = 'edit users';
    case Delete = 'delete users';
}
