<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyTypeRequest;
use App\Http\Resources\PropertyTypeResource;
use App\Models\PropertyType;
use App\Traits\ImagesUtility;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

/** Created and edited in a modal on the list page (ADMIN_UI.md §5). */
class PropertyTypeController extends Controller
{
    use ImagesUtility;

    public function index()
    {
        return Inertia::render('Admin/PropertyTypes/Index', [
            'propertyTypes' => PropertyTypeResource::collection(PropertyType::all()),
        ]);
    }

    public function store(PropertyTypeRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['image'] = $this->storeImage($validatedData['image'], 'propertyType');

        try {
            PropertyType::create($validatedData);
        } catch (\Exception $e) {
            return back()->withErrors(['database' => $this->handleDbErrors($e)]);
        }

        return back();
    }

    public function update(PropertyTypeRequest $request, PropertyType $propertyType)
    {
        $validatedData = $request->validated();

        if ($validatedData['image'] ?? false) {
            if ($propertyType->image) {
                Storage::delete($this->getRelativePath($propertyType->image));
            }
            $validatedData['image'] = $this->storeImage($validatedData['image'], 'propertyType');
        } else {
            unset($validatedData['image']);
        }

        try {
            $propertyType->update($validatedData);
        } catch (\Exception $e) {
            return back()->withErrors(['database' => $this->handleDbErrors($e)]);
        }

        return back();
    }

    public function destroy(PropertyType $propertyType)
    {
        Storage::delete($this->getRelativePath($propertyType->image));
        $propertyType->delete();

        return back();
    }
}
