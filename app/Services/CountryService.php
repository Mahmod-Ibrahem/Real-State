<?php

namespace App\Services;

use App\Models\Country;
use Illuminate\Support\Facades\Cache;

class CountryService
{
    public function all()
    {
        return Cache::rememberForever('countries', fn() => Country::all());
    }
}
