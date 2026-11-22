<?php

namespace Modules\Dashboard\Enums;

use App\Enums\Concerns\IsPermission;

enum DashboardPermission: string
{
    use IsPermission;

    case View = 'view dashboard';
}
