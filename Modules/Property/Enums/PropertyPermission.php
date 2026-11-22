<?php

namespace Modules\Property\Enums;

use App\Enums\Concerns\IsPermission;

enum PropertyPermission: string
{
    use IsPermission;

    case View = 'view properties';
    case Create = 'create properties';
    case Edit = 'edit properties';
    case Delete = 'delete properties';
    case Review = 'review properties';

    case ViewPropertyTypes = 'view property types';
    case CreatePropertyTypes = 'create property types';
    case EditPropertyTypes = 'edit property types';
    case DeletePropertyTypes = 'delete property types';

    case ViewClassifications = 'view property classifications';
    case CreateClassifications = 'create property classifications';
    case EditClassifications = 'edit property classifications';
    case DeleteClassifications = 'delete property classifications';

    case ViewFeatures = 'view features';
    case CreateFeatures = 'create features';
    case EditFeatures = 'edit features';
    case DeleteFeatures = 'delete features';

    case ViewFeatureTypes = 'view feature types';
    case CreateFeatureTypes = 'create feature types';
    case EditFeatureTypes = 'edit feature types';
    case DeleteFeatureTypes = 'delete feature types';

    case ViewOwn = 'view own properties';
    case CreateOwn = 'create own properties';
    case EditOwn = 'edit own properties';
    case PublishOwn = 'publish own properties';

    /** @return list<self> */
    public static function forOwners(): array
    {
        return [self::ViewOwn, self::CreateOwn, self::EditOwn, self::PublishOwn];
    }
}
