<?php

namespace Modules\Property\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Property\Http\Requests\PropertyListingStateRequest;
use Modules\Property\Models\Property;
use Modules\Property\Services\PropertyService;

final class PropertyListingStateController extends Controller
{
    public function __construct(private readonly PropertyService $properties)
    {
    }

    public function __invoke(PropertyListingStateRequest $request, Property $property): RedirectResponse
    {
        $this->properties->updateListingState($property, $request->validated());

        return back();
    }
}
