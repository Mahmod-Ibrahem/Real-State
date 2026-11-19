<?php

namespace Modules\Property\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Property\Http\Requests\PropertyClassificationRequest;
use Modules\Property\Http\Resources\PropertyClassificationeResource;
use Modules\Property\Models\PropertyClassifications;
use Inertia\Inertia;

/** Created and edited in a modal on the list page (ADMIN_UI.md §5). */
class PropertyClassificationController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/PropertyClassifications/Index', [
            'propertyClassifications' => PropertyClassificationeResource::collection(PropertyClassifications::all()),
        ]);
    }

    public function store(PropertyClassificationRequest $request)
    {
        try {
            PropertyClassifications::create($request->validated());
        } catch (\Exception $e) {
            return back()->withErrors(['database' => $this->handleDbErrors($e)]);
        }

        return back();
    }

    public function update(PropertyClassificationRequest $request, PropertyClassifications $propertyClassification)
    {
        try {
            $propertyClassification->update($request->validated());
        } catch (\Exception $e) {
            return back()->withErrors(['database' => $this->handleDbErrors($e)]);
        }

        return back();
    }

    public function destroy(PropertyClassifications $propertyClassification)
    {
        $propertyClassification->delete();

        return back();
    }
}
