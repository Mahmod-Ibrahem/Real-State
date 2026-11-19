<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyClassificationRequest;
use App\Http\Resources\PropertyClassificationeResource;
use App\Models\PropertyClassifications;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $propertyClassifications = PropertyClassificationeResource::collection(PropertyClassifications::all());
        return Inertia::render('Admin/PropertyClassifications/Index', ['propertyClassifications' => $propertyClassifications]);
    }

    public function create()
    {
        return Inertia::render('Admin/PropertyClassifications/Create');
    }

    public function store(PropertyClassificationRequest $request)
    {
        $validatedData = $request->validated();

        try {
            PropertyClassifications::create($validatedData);
        } catch (\Exception $e) {
            $errorMessage = $this->handleDbErrors($e);
            return back()->withErrors(['database' => $errorMessage]);
        }

        return redirect()->route('propertyClassifications.index')->with('success', 'Property Classification created successfully.');
    }

    public function edit(PropertyClassifications $propertyClassification)
    {
        if (!$propertyClassification->exists()) {
            return Inertia::render('404');
        }
        return Inertia::render('Admin/PropertyClassifications/Edit', ['propertyClassification' => new PropertyClassificationeResource($propertyClassification)]);
    }

    public function update(PropertyClassificationRequest $request, PropertyClassifications $propertyClassification)
    {

        $validatedData = $request->validated();

        if (!$propertyClassification->exists()) {
            return Inertia::render('404');
        }
        $propertyClassification->update($validatedData);

        return redirect()->route('propertyClassifications.index')->with('success', 'Property Classification updated successfully.');
    }

    public function destroy(PropertyClassifications $propertyClassification)
    {
        if (!$propertyClassification->exists()) {
            return Inertia::render('404');
        }
        $propertyClassification->delete();
        return redirect()->route('propertyClassifications.index')->with('success', 'Property Classification deleted successfully.');
    }
}
