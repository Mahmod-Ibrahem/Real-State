<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyTypeRequest;
use App\Http\Resources\PropertyTypeResource;
use App\Models\PropertyType;
use App\Traits\ImagesUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PropertyTypeController extends Controller
{

    use ImagesUtility;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $propertyTypes = PropertyTypeResource::collection(PropertyType::all());
        return Inertia::render('Admin/PropertyTypes/Index', ['propertyTypes' => $propertyTypes]);
    }

    public function create()
    {
        return Inertia::render('Admin/PropertyTypes/Create');
    }

    public function store(PropertyTypeRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['image'] = $this->storeImage($validatedData['image'], 'propertyType'); // Store the image path

        try {
            PropertyType::create($validatedData);
        } catch (\Exception $e) {
            $errorMessage = $this->handleDbErrors($e);
            return back()->withErrors(['database' => $errorMessage]);
        }

        return redirect()->route('propertyTypes.index')->with('success', 'Property Type created successfully.');
    }

    public function edit(PropertyType $propertyType)
    {
        if (!$propertyType->exists()) {
            return Inertia::render('404');
        }

        return Inertia::render('Admin/PropertyTypes/Edit', ['propertyType' => new PropertyTypeResource($propertyType)]);
    }

    public function update(PropertyTypeRequest $request, PropertyType $propertyType)
    {
        $validatedData = $request->validated();

        if ($validatedData['image'] ?? false) {
            if ($propertyType->image ?? false) {
                $relativePath = $this->getRelativePath($propertyType->image);
                Storage::delete($relativePath);
            }
            $newStoredImage = $this->storeImage($validatedData['image'], 'propertyType'); //path data
            $validatedData['image'] = $newStoredImage;
        } else {
            $validatedData['image'] = $propertyType->image;
        }
        $propertyType->image = $validatedData['image'];
        $propertyType->update($validatedData);
        $propertyType->save();

        return redirect()->route('propertyTypes.index')->with('success', 'Property Type updated successfully.');
    }

    public function destroy(PropertyType $propertyType)
    {
        $relativePath = $this->getRelativePath($propertyType->image);
        Storage::delete($relativePath);
        $propertyType->delete();
        return redirect()->route('propertyTypes.index')->with('success', 'Property Type deleted successfully.');
    }
}
