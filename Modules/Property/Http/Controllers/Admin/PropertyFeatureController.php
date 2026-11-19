<?php

namespace Modules\Property\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Property\Http\Requests\FeatureRequest;
use Modules\Property\Http\Resources\FeatureResource;
use Modules\Property\Models\Feature;
use Modules\Property\Models\FeatureType;
use Inertia\Inertia;

/** Created and edited in a modal on the list page (ADMIN_UI.md §5). */
class PropertyFeatureController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Features/Index', [
            'features' => FeatureResource::collection(Feature::with('featureType')->get()),
            // Options for the modal's type select; names are in the current locale
            'featureTypes' => FeatureType::all()->map(fn (FeatureType $featureType) => [
                'id' => $featureType->id,
                'name' => $featureType->name,
            ]),
        ]);
    }

    public function store(FeatureRequest $request)
    {
        Feature::create($request->validated());

        return back();
    }

    public function update(FeatureRequest $request, Feature $propertyFeature)
    {
        $propertyFeature->update($request->validated());

        return back();
    }

    public function destroy(Feature $propertyFeature)
    {
        $propertyFeature->delete();

        return back();
    }
}
