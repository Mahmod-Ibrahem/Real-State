<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::query()->with('country');
        $search = request('search');
        if ($search) {
            $cities = $cities->where('name', 'like', '%' . $search . '%');
        }
        return Inertia::render('Admin/Cities/Index', [
            'cities' => CityResource::collection($cities->paginate(10)->withQueryString())
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Cities/Create', ['countries' => CountryResource::collection(Country::all())]);
    }

    public function store(CityRequest $request)
    {

        $validatedData = $request->validated();

        try {
            City::create($validatedData);
        } catch (\Exception $e) {
            $errorMessage = $this->handleDbErrors($e);
            return back()->withErrors(['database' => $errorMessage]);
        }

        return redirect()->route('cities.index', ['locale' => 'en'])->with('success', 'City created successfully.');
    }

    public function edit(City $city)
    {
        if (!$city->exists()) {
            return Inertia::render('404');
        }
        return Inertia::render('Admin/Cities/Edit', [
            'city' => new CityResource($city),
            'countries' => CountryResource::collection(Country::all()),
        ]);
    }

    public function update(CityRequest $request, City $city)
    {
        $validatedData = $request->validated();

        $city->update($validatedData);
        $city->save();
        return redirect()->route('cities.index')->with('success', 'City updated successfully.');
    }

    public function destroy(City $city)
    {
        if (!$city->exists()) {
            return Inertia::render('404');
        }

        if ($city->areas()->exists() || $city->properties()->exists()) {
            return back()->withErrors(['database' => 'This city still has areas or properties. Move or delete them first.']);
        }

        $city->delete();

        return redirect()->route('cities.index')->with('success', 'City deleted successfully.');
    }
}
