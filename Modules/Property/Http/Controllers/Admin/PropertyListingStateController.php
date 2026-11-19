<?php

namespace Modules\Property\Http\Controllers\Admin;

use Modules\Property\Enums\PropertyStatus;
use App\Http\Controllers\Controller;
use Modules\Property\Http\Requests\PropertyListingStateRequest;
use Modules\Property\Models\Property;

/** Saves the quick edits made in the properties table: visibility, purpose and status (ADMIN_UI.md §5). */
class PropertyListingStateController extends Controller
{
    public function __invoke(PropertyListingStateRequest $request, Property $property)
    {
        $property->fill($request->validated());

        // Switching purpose can leave an impossible status (a "Sold" rental): fall back to Available
        if (!in_array($property->status, PropertyStatus::allowedFor($property->purpose), true)) {
            $property->status = PropertyStatus::Available;
        }

        $property->save();

        return back();
    }
}
