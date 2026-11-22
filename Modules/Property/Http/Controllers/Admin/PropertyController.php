<?php

namespace Modules\Property\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Property\Enums\ListingPurpose;
use Modules\Property\Enums\PropertyStatus;
use Modules\Property\Enums\ReviewStatus;
use Modules\Property\Http\Requests\IndexPropertyRequest;
use Modules\Property\Http\Requests\StorePropertyRequest;
use Modules\Property\Http\Requests\UpdatePropertyRequest;
use Modules\Property\Http\Resources\PropertyFormOptionsResource;
use Modules\Property\Http\Resources\PropertyImageResource;
use Modules\Property\Http\Resources\PropertyListResource;
use Modules\Property\Http\Resources\PropertyResource;
use Modules\Property\Models\Property;
use Modules\Property\Services\PropertyFormOptionsService;
use Modules\Property\Services\PropertyService;

final class PropertyController extends Controller
{
    public function __construct(
        private readonly PropertyService $properties,
        private readonly PropertyFormOptionsService $formOptions,
    ) {
    }

    public function index(IndexPropertyRequest $request): Response
    {
        return Inertia::render('Admin/Properties/Index', [
            'properties' => PropertyListResource::collection(
                $this->properties->paginateForAdmin($request->search(), $request->reviewStatus())
            ),
            'review_filter' => $request->reviewStatus()?->value,
            'purposes' => ListingPurpose::options(),
            'statuses' => PropertyStatus::options(),
            'review_statuses' => ReviewStatus::options(),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Admin/Properties/Create', $this->formProps($request));
    }

    public function store(StorePropertyRequest $request): RedirectResponse
    {
        $this->properties->create($request->validated(), $request->user(), ReviewStatus::Approved);

        return to_route('admin.properties.index');
    }

    public function edit(Request $request, Property $property): Response
    {
        $property = $this->properties->loadForEdit($property);

        return Inertia::render('Admin/Properties/Edit', [
            ...$this->formProps($request),
            'property' => PropertyResource::make($property),
            'gallery' => PropertyImageResource::collection($property->propertyImages)->resolve($request),
            'review_statuses' => ReviewStatus::options(),
        ]);
    }

    public function update(UpdatePropertyRequest $request, Property $property): RedirectResponse
    {
        $this->properties->update($property, $request->validated());

        return to_route('admin.properties.index');
    }

    public function destroy(Property $property): RedirectResponse
    {
        $this->properties->delete($property);

        return to_route('admin.properties.index');
    }

    private function formProps(Request $request): array
    {
        return PropertyFormOptionsResource::make($this->formOptions->all())->resolve($request);
    }
}
