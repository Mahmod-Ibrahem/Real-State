<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class FeatureTypeController extends Controller
{
    public function index()
    {
        $featureTypes = \App\Models\FeatureType::all()->map(function ($featureType) {
            return [
                'id' => $featureType->id,
                'name' => $featureType->name,
                'featureTypeName' => $featureType->name
            ];
        });
        return Inertia::render('Admin/FeatureTypes/Index', ['featureTypes' => $featureTypes]);
    }

    public function create()
    {
        return Inertia::render('Admin/FeatureTypes/Create');
    }

    public function store()
    {
        $featureType = \App\Models\FeatureType::create(request()->all());
        return redirect()->route('feature-types.index');
    }

    public function edit(string $featureType)
    {
        $featureType = \App\Models\FeatureType::findOrFail($featureType);
        return Inertia::render('Admin/FeatureTypes/Edit', ['featureType' => [
            'id' => $featureType->id,
            'name' => $featureType->name
        ]]);
    }

    public function update(string $featureType)
    {
        $featureType = \App\Models\FeatureType::findOrFail($featureType);
        $featureType->update(request()->all());
        return redirect()->route('feature-types.index');
    }

    public function destroy(string $featureType)
    {
        $featureType = \App\Models\FeatureType::findOrFail($featureType);
        $featureType->delete();
        return redirect()->route('feature-types.index');
    }
}
