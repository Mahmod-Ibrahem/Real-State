<?php

namespace Modules\Property\Services;

use Modules\Property\Http\Resources\Users\PropertyListResource;
use Modules\Property\Models\PropertyClassifications;
use Illuminate\Support\Facades\Cache;

class ClassificationService
{
    public function all()
    {
        return Cache::rememberForever('classifications', function () {
            return PropertyClassifications::whereHas('properties', fn($q) => $q->active())
                ->get();
        });
    }
}
