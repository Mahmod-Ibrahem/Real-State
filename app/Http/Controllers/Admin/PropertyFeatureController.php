<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeatureRequest;
use App\Http\Resources\FeatureResource;
use App\Models\Feature;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyFeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $features = FeatureResource::collection(Feature::with('featureType')->get());
        return Inertia::render('Admin/Features/Index', ['features' => $features]);
    }

    public function create()
    {
        // Same {id, name} shape as edit(); name is translated to the current locale
        $featureTypes = \App\Models\FeatureType::all()->map(fn ($featureType) => [
            'id' => $featureType->id,
            'name' => $featureType->name,
        ]);
        return Inertia::render('Admin/Features/Create',
            ['featureTypes' => $featureTypes]);
    }

    public function store(FeatureRequest $request)
    {
        $validatedData = $request->validated();

        Feature::create($validatedData);

        return redirect()->route('propertyFeatures.index')->with('success', 'Features created successfully.');
    }

    public function edit(Feature $propertyFeature)
    {
        if (!$propertyFeature->exists()) {
            return Inertia::render('404');
        }
        $featureTypes = \App\Models\FeatureType::all()->map(function ($featureType) {
            return [
                'id' => $featureType->id,
                'name' => $featureType->name,
            ];
        });
        return Inertia::render('Admin/Features/Edit', ['feature' => new FeatureResource($propertyFeature),
            'featureTypes' => $featureTypes]);
    }

    public function update(FeatureRequest $request, Feature $propertyFeature)
    {

        if (!$propertyFeature) {
            return Inertia::render('404');
        }
        $validatedData = $request->validated();
        $propertyFeature->update($validatedData);

        return redirect()->route('propertyFeatures.index')->with('success', 'Features updated successfully.');
    }

    public function destroy(Feature $propertyFeature)
    {
        if (!$propertyFeature->exists()) {
            return Inertia::render('404');
        }
        $propertyFeature->delete();
        return redirect()->route('propertyFeatures.index')->with('success', 'Features deleted successfully.');
    }
}
