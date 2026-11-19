<?php

namespace Modules\Property\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Property\Models\FeatureType;
use Illuminate\Http\Request;
use Inertia\Inertia;

/** Created and edited in a modal on the list page (ADMIN_UI.md §5). */
class FeatureTypeController extends Controller
{
    private const RULES = ['name' => ['required', 'string', 'max:255']];

    public function index()
    {
        return Inertia::render('Admin/FeatureTypes/Index', [
            'featureTypes' => FeatureType::withCount('features')->get()->map(fn (FeatureType $featureType) => [
                'id' => $featureType->id,
                'name' => $featureType->name,
                'featuresCount' => $featureType->features_count,
            ]),
        ]);
    }

    public function store(Request $request)
    {
        FeatureType::create($request->validate(self::RULES));

        return back();
    }

    public function update(Request $request, FeatureType $featureType)
    {
        $featureType->update($request->validate(self::RULES));

        return back();
    }

    public function destroy(FeatureType $featureType)
    {
        if ($featureType->features()->exists()) {
            return back()->withErrors(['database' => 'This feature type still has features. Move or delete them first.']);
        }

        $featureType->delete();

        return back();
    }
}
