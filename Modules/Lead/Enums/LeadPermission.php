<?php

namespace Modules\Lead\Enums;

use App\Enums\Concerns\IsPermission;

enum LeadPermission: string
{
    use IsPermission;

    case ViewPropertyLeads = 'view property leads';
    case EditPropertyLeads = 'edit property leads';
    case DeletePropertyLeads = 'delete property leads';

    case ViewContactLeads = 'view contact leads';
    case EditContactLeads = 'edit contact leads';
    case DeleteContactLeads = 'delete contact leads';
}
