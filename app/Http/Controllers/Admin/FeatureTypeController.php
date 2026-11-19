<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeatureType;
use Illuminate\Http\Request;
use Inertia\Inertia;

/** Created and edited in a modal on the list page (ADMIN_UI.md §5). */
class FeatureTypeController extends Controller
{
    private const RULES = ['name' => ['required', 'string', 'max:255']];

    public function index()
    {
        return Inertia::render('Admin/FeatureTypes/Index', [
            'featureTypes' => FeatureType::all()->map(fn (FeatureType $featureType) => [
                'id' => $featureType->id,
                'name' => $featureType->name,
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
        $featureType->delete();

        return back();
    }
}
