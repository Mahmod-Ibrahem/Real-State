<?php

namespace App\Enums;

use App\Enums\Concerns\IsPermission;

enum NotificationPermission: string
{
    use IsPermission;

    case Receive = 'receive notifications';
    case PropertySubmissions = 'receive property submission notifications';
}
