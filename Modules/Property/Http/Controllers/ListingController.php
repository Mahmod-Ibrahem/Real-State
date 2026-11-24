<?php

namespace Modules\Property\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Location\Http\Resources\AreaResource;
use Modules\Location\Http\Resources\CityResource;
use Modules\Location\Http\Resources\CountryResource;
use Modules\Property\Http\Resources\PropertyClassificationeResource;
use Modules\Property\Http\Resources\Users\PropertyResource;
use Modules\Property\Http\Resources\Users\PropertyTypeResource;
use Modules\Property\Models\Property;
use Modules\Location\Services\AreaService;
use Modules\Location\Services\CityService;
use Modules\Property\Services\ClassificationService;
use Modules\Location\Services\CountryService;
use Modules\Property\Services\PropertyDetailService;
use Modules\Property\Services\PropertyTypeService;
use Modules\Property\Enums\ListingPurpose;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ListingController extends Controller
{
    public function __construct(
        private CityService    $cityService, private PropertyTypeService $propertyTypeService,
        private AreaService    $areaService,
        private CountryService $countryService, private ClassificationService $classificationService,
        private readonly PropertyDetailService $details,
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
                'properties' => PropertyResource::collection($properties),
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
            'properties' => PropertyResource::collection($properties),
        ], $this->getListingFilters()));
    }

    public function show(string $listing): Response
    {
        $property = $this->details->findListed($listing);

        return Inertia::render('Users/PropertyDetails', [
            'metaTitle' => $property->name,
            'metaDescription' => $property->description,
            'property' => PropertyResource::make($property),
            'relatedProperties' => PropertyResource::collection($this->details->relatedTo($property)),
            'features' => $this->details->featuresByType($property)
                ->map(fn (Collection $features) => $features->map->only(['id', 'name'])),
        ]);
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
