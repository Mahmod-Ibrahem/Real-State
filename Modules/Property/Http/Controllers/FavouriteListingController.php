<?php

namespace Modules\Property\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Property\Http\Requests\FavouriteRequest;
use Modules\Property\Http\Resources\Users\PropertyResource;
use Modules\Property\Models\Property;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;

class FavouriteListingController extends Controller
{
    public function index()
    {
        $favouritesPropIds = json_decode(Cookie::get('favourite', '[]'));
        $Properties = Property::whereIn('id', $favouritesPropIds)->get();
        return Inertia::render('Favourite', ['properties' => PropertyResource::collection($Properties)]);
    }

    public function store(FavouriteRequest $request)
    {
        $validatedData = $request->validated();

        // Get the cookie and decode it into an array
        $favourite = json_decode(Cookie::get('favourite', '[]'), true);

        // Add the new property id
        $favourite[] = $validatedData['property_id'];

        // Encode back to JSON and store
        Cookie::queue('favourite', json_encode($favourite), 60 * 24 * 30); // 30 days expiration (optional)
        return redirect()->back();

    }


    public function destroy(string $id)
    {
        $favourite = json_decode(Cookie::get('favourite', '[]'), true);

        if (!is_array($favourite)) {
            $favourite = [];
        }

        $favourite = array_filter($favourite, function ($item) use ($id) {
            return $item != $id;
        });

        Cookie::queue('favourite', json_encode(array_values($favourite)), 60 * 24 * 30);

        return redirect()->back();
    }
}
