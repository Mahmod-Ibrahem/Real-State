<?php

namespace Modules\Property\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Property\Enums\ReviewStatus;
use Modules\Property\Http\Requests\Owner\StoreOwnerPropertyRequest;
use Modules\Property\Http\Requests\Owner\UpdateOwnerPropertyRequest;
use Modules\Property\Http\Resources\Owner\OwnerPropertyListResource;
use Modules\Property\Http\Resources\PropertyFormOptionsResource;
use Modules\Property\Http\Resources\PropertyImageResource;
use Modules\Property\Http\Resources\PropertyResource;
use Modules\Property\Models\Property;
use Modules\Property\Services\OwnerPropertyService;
use Modules\Property\Services\PropertyFormOptionsService;
use Modules\Property\Services\PropertyService;

final class PropertyController extends Controller
{
    public function __construct(
        private readonly OwnerPropertyService $ownerProperties,
        private readonly PropertyService $properties,
        private readonly PropertyFormOptionsService $formOptions,
    ) {
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Owner/Properties/Index', [
            'properties' => OwnerPropertyListResource::collection($this->ownerProperties->paginateFor($request->user())),
            'review_statuses' => ReviewStatus::options(),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Owner/Properties/Create', $this->formProps($request));
    }

    public function store(StoreOwnerPropertyRequest $request): RedirectResponse
    {
        $this->ownerProperties->submit($request->user(), $request->validated());

        return to_route('owner.properties.index');
    }

    public function edit(Request $request, Property $property): Response
    {
        $property = $this->properties->loadForEdit($property);

        return Inertia::render('Owner/Properties/Edit', [
            ...$this->formProps($request),
            'property' => PropertyResource::make($property),
            'gallery' => PropertyImageResource::collection($property->propertyImages)->resolve($request),
            'review_statuses' => ReviewStatus::options(),
        ]);
    }

    public function update(UpdateOwnerPropertyRequest $request, Property $property): RedirectResponse
    {
        $this->ownerProperties->update($property, $request->validated());

        return to_route('owner.properties.index');
    }

    private function formProps(Request $request): array
    {
        return PropertyFormOptionsResource::make($this->formOptions->all())->resolve($request);
    }
}
