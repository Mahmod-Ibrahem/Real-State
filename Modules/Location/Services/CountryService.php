<?php

namespace Modules\Location\Services;

use Modules\Location\Models\Country;
use Illuminate\Support\Facades\Cache;

class CountryService
{
    public function all()
    {
        return Cache::rememberForever('countries', fn() => Country::all());
    }
}
