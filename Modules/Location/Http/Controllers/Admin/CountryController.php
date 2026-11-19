<?php

namespace Modules\Location\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Location\Http\Requests\CountryRequest;
use Modules\Location\Http\Resources\CountryResource;
use Modules\Location\Models\Country;
use Inertia\Inertia;

/** Created and edited in a modal on the list page (ADMIN_UI.md §5). */
class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::query()
            ->when(request('search'), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"));

        return Inertia::render('Admin/Countries/Index', [
            'countries' => CountryResource::collection($countries->paginate(10)->withQueryString()),
        ]);
    }

    public function store(CountryRequest $request)
    {
        Country::create($request->validated());

        return back();
    }

    public function update(CountryRequest $request, Country $country)
    {
        $country->setTranslation('name', app()->getLocale(), $request->validated('name'));
        $country->save();

        return back();
    }

    public function destroy(Country $country)
    {
        if ($country->cities()->exists() || $country->properties()->exists()) {
            return back()->withErrors(['database' => 'This country still has cities or properties. Move or delete them first.']);
        }

        $country->delete();

        return back();
    }
}
