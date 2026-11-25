<?php

namespace Modules\Property\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Property\Enums\PaymentType;
use Modules\Property\Http\Requests\IndexListingRequest;
use Modules\Property\Http\Resources\Users\PropertyResource;
use Modules\Property\Models\Property;
use Modules\Property\Services\ListingFilterService;
use Modules\Property\Services\ListingSearchService;
use Modules\Property\Services\PropertyDetailService;
use Modules\Property\Enums\ListingPurpose;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ListingController extends Controller
{
    public function __construct(
        private readonly ListingSearchService $search,
        private readonly ListingFilterService $filterOptions,
        private readonly PropertyDetailService $details,
    ) {
    }

    /**
     * All published properties, optionally filtered (e.g. /listing?purpose=sale replaces the old Buy page).
     */
    public function index(IndexListingRequest $request): Response
    {
        $filters = $request->filters();

        return Inertia::render('Users/Listing', [
            ...$this->metaFor(ListingPurpose::tryFrom((string) $filters['purpose'])),
            'properties' => PropertyResource::collection($this->search->paginate($filters)),
            'filters' => $filters,
            'payment_types' => PaymentType::options(),
            ...$this->filterOptions->getFilters(),
        ]);
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
        ], $this->filterOptions->getFilters()));
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
}
