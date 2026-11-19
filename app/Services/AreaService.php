<?php

namespace App\Services;

use App\Models\Area;
use App\Models\City;
use Illuminate\Support\Facades\Cache;

class AreaService
{
    public function all()
    {
        return Cache::rememberForever('areas', fn() => Area::whereHas('properties', fn($q) => $q->active())->get());
    }
}
