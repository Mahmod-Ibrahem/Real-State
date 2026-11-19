<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Models\City;
use App\Models\Country;
use Inertia\Inertia;

/** Created and edited in a modal on the list page (ADMIN_UI.md §5). */
class CityController extends Controller
{
    public function index()
    {
        $cities = City::query()->with('country')
            ->when(request('search'), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"));

        return Inertia::render('Admin/Cities/Index', [
            'cities' => CityResource::collection($cities->paginate(10)->withQueryString()),
            // Options for the modal's country select
            'countries' => CountryResource::collection(Country::all()),
        ]);
    }

    public function store(CityRequest $request)
    {
        try {
            City::create($request->validated());
        } catch (\Exception $e) {
            return back()->withErrors(['database' => $this->handleDbErrors($e)]);
        }

        return back();
    }

    public function update(CityRequest $request, City $city)
    {
        try {
            $city->update($request->validated());
        } catch (\Exception $e) {
            return back()->withErrors(['database' => $this->handleDbErrors($e)]);
        }

        return back();
    }

    public function destroy(City $city)
    {
        if ($city->areas()->exists() || $city->properties()->exists()) {
            return back()->withErrors(['database' => 'This city still has areas or properties. Move or delete them first.']);
        }

        $city->delete();

        return back();
    }
}
