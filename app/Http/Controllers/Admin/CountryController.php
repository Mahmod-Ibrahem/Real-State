<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::query();
        $search = request('search');

        if ($search) {
            $countries = $countries->where('name', 'like', '%' . $search . '%');
        }

        return Inertia::render('Admin/Countries/Index', [
            'countries' => CountryResource::collection($countries->paginate(10)->withQueryString())
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Countries/Create');
    }

    public function edit(Country $country)
    {
        if (!$country->exists()) {
            return Inertia::render('404');
        }

        return Inertia::render('Admin/Countries/Edit', [
            'country' => new CountryResource($country)
        ]);
    }

    public function store(CountryRequest $request)
    {
        $validatedData = $request->validated();
        Country::create($validatedData);
        return redirect()->route('countries.index');
    }

    public function update(CountryRequest $request, Country $country)
    {
        if (!$country->exists()) {
            return Inertia::render('404');
        }

        $validatedData = $request->validated();
        $country->setTranslation('name', app()->getLocale(), $validatedData['name']);
        $country->save();

        return redirect()->route('countries.index');
    }

    public function destroy(Country $country)
    {
        if (!$country->exists()) {
            return Inertia::render('404');
        }

        if ($country->cities()->exists() || $country->properties()->exists()) {
            return back()->withErrors(['database' => 'This country still has cities or properties. Move or delete them first.']);
        }

        $country->delete();
        return redirect()->route('countries.index');
    }
}
