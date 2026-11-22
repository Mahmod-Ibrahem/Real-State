<?php

namespace Modules\Location\Enums;

use App\Enums\Concerns\IsPermission;

enum LocationPermission: string
{
    use IsPermission;

    case ViewCountries = 'view countries';
    case CreateCountries = 'create countries';
    case EditCountries = 'edit countries';
    case DeleteCountries = 'delete countries';

    case ViewCities = 'view cities';
    case CreateCities = 'create cities';
    case EditCities = 'edit cities';
    case DeleteCities = 'delete cities';

    case ViewAreas = 'view areas';
    case CreateAreas = 'create areas';
    case EditAreas = 'edit areas';
    case DeleteAreas = 'delete areas';
}
