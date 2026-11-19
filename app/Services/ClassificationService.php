<?php

namespace App\Services;

use App\Http\Resources\Users\PropertyListResource;
use App\Models\PropertyClassifications;
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
