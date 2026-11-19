<?php

namespace Modules\Location\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Location\Http\Requests\AreaRequest;
use Modules\Location\Http\Resources\AreaResource;
use Modules\Location\Http\Resources\CityResource;
use Modules\Location\Models\Area;
use Modules\Location\Models\City;
use Inertia\Inertia;

/** Created and edited in a modal on the list page (ADMIN_UI.md §5). */
class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::query()->with('city')
            ->when(request('search'), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"));

        return Inertia::render('Admin/Areas/Index', [
            'areas' => AreaResource::collection($areas->paginate(10)->withQueryString()),
            // Options for the modal's city select, with their country for "Cairo (Egypt)" labels
            'cities' => CityResource::collection(City::with('country')->get()),
        ]);
    }

    public function store(AreaRequest $request)
    {
        try {
            Area::create($request->validated());
        } catch (\Exception $e) {
            return back()->withErrors(['database' => $this->handleDbErrors($e)]);
        }

        return back();
    }

    public function update(AreaRequest $request, Area $area)
    {
        $validatedData = $request->validated();
        $area->setTranslation('name', app()->getLocale(), $validatedData['name']);
        $area->city_id = $validatedData['city_id'];

        try {
            $area->save();
        } catch (\Exception $e) {
            return back()->withErrors(['database' => $this->handleDbErrors($e)]);
        }

        return back();
    }

    public function destroy(Area $area)
    {
        if ($area->properties()->exists()) {
            return back()->withErrors(['database' => 'This area still has properties. Move or delete them first.']);
        }

        $area->delete();

        return back();
    }
}
