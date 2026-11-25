<?php

namespace Modules\Lead\Enums;

use App\Enums\Concerns\IsPermission;

enum LeadPermission: string
{
    use IsPermission;

    case ViewPropertyLeads = 'view property leads';
    case CreatePropertyLeads = 'create property leads';
    case EditPropertyLeads = 'edit property leads';
    case DeletePropertyLeads = 'delete property leads';
    case ViewOwnPropertyLeads = 'view own property leads';
    case CreateOwnPropertyLeads = 'create own property leads';
    case EditOwnPropertyLeads = 'edit own property leads';

    case ViewContactLeads = 'view contact leads';
    case EditContactLeads = 'edit contact leads';
    case DeleteContactLeads = 'delete contact leads';

    /** @return list<self> */
    public static function forOwners(): array
    {
        return [self::ViewOwnPropertyLeads, self::CreateOwnPropertyLeads, self::EditOwnPropertyLeads];
    }
}
