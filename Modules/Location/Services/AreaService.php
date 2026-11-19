<?php

namespace Modules\Location\Services;

use Modules\Location\Models\Area;
use Modules\Location\Models\City;
use Illuminate\Support\Facades\Cache;

class AreaService
{
    public function all()
    {
        return Cache::rememberForever('areas', fn() => Area::whereHas('properties', fn($q) => $q->active())->get());
    }
}
