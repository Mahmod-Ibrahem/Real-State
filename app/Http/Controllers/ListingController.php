<?php

namespace App\Http\Controllers;

use App\Http\Resources\AreaResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\PropertyClassificationeResource;
use App\Http\Resources\Users\propertyResource;
use App\Http\Resources\Users\propertyTypeResource;
use App\Models\Feature;
use App\Models\Property;
use App\Services\AreaService;
use App\Services\CityService;
use App\Services\ClassificationService;
use App\Services\CountryService;
use App\Services\PropertyTypeService;
use App\Enums\ListingPurpose;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ListingController extends Controller
{
    public function __construct(
        private CityService    $cityService, private PropertyTypeService $propertyTypeService,
        private AreaService    $areaService,
        private CountryService $countryService, private ClassificationService $classificationService,
    )
    {

    }

    /**
     * All published properties, optionally filtered (e.g. /listing?purpose=sale replaces the old Buy page).
     */
    public function index(Request $request)
    {
        $filters = $this->filtersFrom($request);

        $properties = $this->applyFilters(Property::query()->active(), $filters)
            ->with('propertyType')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Users/Listing', array_merge(
            $this->metaFor(ListingPurpose::tryFrom((string) $filters['purpose'])),
            [
                'properties' => propertyResource::collection($properties),
                'filters' => $filters,
            ],
            $this->getListingFilters()
        ));
    }

    /** Normalised filter values from the query string; ids are ints so the sidebar selects match. */
    private function filtersFrom(Request $request): array
    {
        $id = fn (string $key) => $request->integer($key) ?: null;

        return [
            'search' => $request->string('search')->trim()->toString() ?: null,
            'purpose' => ListingPurpose::tryFrom((string) $request->query('purpose'))?->value,
            'type' => $id('type'),
            'city' => $id('city'),
            'area' => $id('area'),
            'classification' => $id('classification'),
            'country' => $id('country'),
        ];
    }

    private function applyFilters($query, array $filters)
    {
        foreach (array_filter($filters) as $key => $value) {
            $column = $this->mapFilterToColumn($key);
            $key === 'search'
                ? $query->where($column, 'like', '%' . $value . '%')
                : $query->where($column, $value);
        }

        return $query;
    }

    /** SEO title/description: the Sale and Rent views keep the old Buy / Rent page wording. */
    private function metaFor(?ListingPurpose $purpose): array
    {
        return match ($purpose) {
            ListingPurpose::Sale => [
                'metaTitle' => 'Buy Property in Egypt | Apartments, Villas & Houses for Sale – Qeima',
                'metaDescription' => 'Browse properties for sale in Egypt with Qeima. Find the perfect apartment, villa, or house in Cairo, Alexandria, and more. Verified listings, detailed prices, and direct contact with sellers.',
            ],
            ListingPurpose::Rent => [
                'metaTitle' => 'Rent Property in Egypt | Apartments, Villas & Offices for Rent – Qeima',
                'metaDescription' => 'Find rental properties in Egypt with Qeima. Browse apartments, villas, and offices for rent in Cairo, Alexandria, and other top locations. Verified listings with prices, photos, and easy contact options.',
            ],
            null => [
                'metaTitle' => 'Explore All Properties in Egypt | Buy, Rent Apartments, Villas & More – Qeima',
                'metaDescription' => 'Browse all real estate listings on Qeima. Discover properties for sale and rent across Egypt including apartments, villas, houses, offices, and commercial spaces. Updated daily with verified listings, prices, and photos.',
            ],
        };
    }

    public function sellBuyRent(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string',
            'purpose' => ['nullable', Rule::enum(ListingPurpose::class)],
            'type' => 'nullable|integer',
            'areaId' => 'required|integer',
            'cityId' => 'required|integer',
        ]);

        $properties = Property::query();

        if ($request->has('search')) {
            $properties->where('name', 'like', '%' . $request->search . '%');
        }
        $properties = $properties->where('purpose', $request->purpose)
            ->where('property_type_id', $request->type)
            ->where('area_id', $request->areaId)
            ->where('city_id', $request->cityId)
            ->with('propertyType')->paginate(10);

        return Inertia::render('Users/Listing', array_merge([
            'properties' => propertyResource::collection($properties),
        ], $this->getListingFilters()));
    }

    public function show(string $listing)
    {
        $property = Cache::remember("property.{$listing}", now()->addHour(1), function () use ($listing) {
            return Property::where('slug->' . app()->getLocale(), $listing)
                ->orWhere('slug->en', $listing)
                ->with('propertyImages')
                ->first();
        });

        $features = Feature::whereHas('properties', function ($q) use ($property) {
            $q->where('properties.id', $property->id);
        })->whereHas('featureType')
            ->with('featureType')->get()->groupBy('featureType.name')->map(function ($features) {
                return $features->map(function ($feature) {
                    return [
                        'id' => $feature->id,
                        'name' => $feature->name,
                    ];
                });
            });
        $relatedProperties = Property::where('property_type_id', $property->property_type_id)->where('id', ' != ', $property->id)->get();

        return Inertia::render('Users/PropertyDetails',
            [
                'metaTitle' => $property->name,
                'metaDescription' => $property->description,
                'property' => new propertyResource($property),
                'relatedProperties' => propertyResource::collection($relatedProperties),
                'features' => $features]);
    }

    private function mapFilterToColumn($filter)
    {
        $map = [
            'search' => 'name',
            'purpose' => 'purpose',
            'type' => 'property_type_id',
            'city' => 'city_id',
            'area' => 'area_id',
            'classification' => 'property_classification_id',
            'country' => 'country_id'
        ];

        return $map[$filter] ?? $filter;
    }

    protected function getListingFilters(): array
    {
        return [
            'cities' => CityResource::collection($this->cityService->all()),
            'propertyType' => PropertyTypeResource::collection($this->propertyTypeService->all()),
            'purposes' => ListingPurpose::options(),
            'classifications' => PropertyClassificationeResource::collection($this->classificationService->all()),
            'areas' => AreaResource::collection($this->areaService->all()),
            'countries' => CountryResource::collection($this->countryService->all()),
        ];
    }
}
