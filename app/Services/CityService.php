<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Support\Facades\Cache;

class CityService
{
    public function all()
    {
        return Cache::rememberForever('cities', fn() => City::whereHas('properties', fn($q) => $q->active())->get());
    }
}
