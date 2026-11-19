<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AreaRequest;
use App\Http\Resources\AreaResource;
use App\Http\Resources\CityResource;
use App\Models\Area;
use App\Models\City;
use Inertia\Inertia;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $areas = Area::query()->with('city');
        $search = request('search');
        if ($search) {
            $areas = $areas->where('name', 'like', '%' . $search . '%');
        }
        return Inertia::render('Admin/Areas/Index', ['areas' => AreaResource::collection($areas->paginate(10)->withQueryString())]);
    }

    public function create()
    {
        return Inertia::render('Admin/Areas/Create', ['cities' => $this->cityOptions()]);
    }

    public function edit(Area $area)
    {
        return Inertia::render('Admin/Areas/Edit', [
            'area' => new AreaResource($area),
            'cities' => $this->cityOptions(),
        ]);
    }

    public function store(AreaRequest $request)
    {
        try {
            Area::create($request->validated());
        } catch (\Exception $e) {
            return back()->withErrors(['database' => $this->handleDbErrors($e)]);
        }

        return redirect()->route('areas.index');
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

        return redirect()->route('areas.index');
    }

    public function destroy(Area $area)
    {
        if ($area->properties()->exists()) {
            return back()->withErrors(['database' => 'This area still has properties. Move or delete them first.']);
        }

        $area->delete();
        return redirect()->route('areas.index');
    }

    /** Cities with their country name, e.g. "Cairo (Egypt)", for the parent select. */
    private function cityOptions()
    {
        return CityResource::collection(City::with('country')->get());
    }
}
