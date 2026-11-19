<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PropertyStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyListingStateRequest;
use App\Models\Property;

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
