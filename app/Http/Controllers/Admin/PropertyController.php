<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ListingPurpose;
use App\Enums\PropertyStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyRequest;
use App\Http\Resources\AreaResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\FeatureResource;
use App\Http\Resources\PropertyClassificationeResource;
use App\Http\Resources\PropertyListResource;
use App\Http\Resources\PropertyResource;
use App\Http\Resources\PropertyTypeResource;
use App\Models\Area;
use App\Models\City;
use App\Models\Country;
use App\Models\Feature;
use App\Models\Property;
use App\Models\PropertyClassifications;
use App\Models\PropertyImages;
use App\Models\PropertyType;
use App\Traits\ImagesUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PropertyController extends Controller
{

    use ImagesUtility;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $properties = Property::query();
        if ($search) {
            $properties = $properties->where('name', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%')
                ->orWhere('bathrooms', '=', $search)
                ->orWhere('bedrooms', '=', $search)
                ->orWhere('sqft', '=', $search)
                ->orWhere('price', '=', $search)
                ->orWhere('id', '=', $search);
        }
        $properties = $properties->with(['city', 'propertyType', 'area'])
            ->paginate(10)->withQueryString();

        return Inertia::render('Admin/Properties/Index', [
            'properties' => PropertyListResource::collection($properties),
            // Options for the table's purpose toggle and status dropdown
            'purposes' => ListingPurpose::options(),
            'statuses' => PropertyStatus::options(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $propertyTypes = PropertyTypeResource::collection(PropertyType::all());
        $cities = CityResource::collection(City::all());
        $areas = AreaResource::collection(Area::all());
        $features = FeatureResource::collection(Feature::all());
        $propertyClassification = PropertyClassificationeResource::collection(PropertyClassifications::all());
        $countries = CountryResource::collection(Country::all());
        return Inertia::render('Admin/Properties/Create',
            ['cities' => $cities, 'areas' => $areas, 'purposes' => ListingPurpose::options(), 'statuses' => PropertyStatus::options(), 'features' => $features, 'propertyClassification' => $propertyClassification
                , 'propertyTypes' => $propertyTypes, 'countries' => $countries]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(PropertyRequest $request)
    {
        $validatedData = $request->validated();

        if ($this->hasPropertyTitle($validatedData['name'])) {
            return redirect()->route('properties.index')->with('error', 'Property title already exists.');
        }

        DB::beginTransaction(); // Start Transaction

        try {
            // Store the main property record
            $validatedData['image'] = $this->storeImage($validatedData['image'], 'property');
            $property = Property::create($validatedData);

            $this->addGalleryImages($property, $validatedData['propertyImages']);

            // Store Property Features
            $this->setPropertyFeatures($property, $validatedData['feature_ids']);

            DB::commit(); // Commit Transaction

            return redirect()->route('properties.index')->with('success', 'Property created successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback Transaction on Error
            return back()->withErrors(['database' => $this->handleDbErrors($e)]);
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        if (!$property->exists()) {
            return Inertia::render('404');
        } else {
            $propertyTypes = PropertyTypeResource::collection(PropertyType::all());
            $cities = CityResource::collection(City::all());
            $areas = AreaResource::collection(Area::all());
            $features = FeatureResource::collection(Feature::all());
            $propertyClassification = PropertyClassificationeResource::collection(PropertyClassifications::all());
            $countries = CountryResource::collection(Country::all());
            return Inertia::render('Admin/Properties/Edit',
                ['cities' => $cities, 'areas' => $areas, 'purposes' => ListingPurpose::options(), 'statuses' => PropertyStatus::options(), 'features' => $features, 'propertyClassification' => $propertyClassification
                    , 'propertyTypes' => $propertyTypes, 'property' => PropertyResource::make($property), 'countries' => $countries,
                    // Saved gallery photos for the form's gallery manager
                    'gallery' => $property->propertyImages()->get(['id', 'image'])
                        ->map(fn ($image) => ['id' => $image->id, 'url' => $image->image]),
                ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PropertyRequest $request, Property $property)
    {

        $validatedData = $request->validated();
        if (!$property->exists()) {
            return Inertia::render('404');
        } else {
            //Check If Property title already exists
            if ($this->hasPropertyTitle($validatedData['name'])) {
                return redirect()->back()->with('error', 'Property title already exists.');
            }
            // Check If The Property Has Images
            if (isset($validatedData['image'])) {
                $relativePath = $this->getRelativePath($property->image);
                $validatedData['image'] = $this->storeImage($validatedData['image'], 'property'); //path data
            } else {
                $validatedData['image'] = $property->image;
            }
            //Update Property Features
            $this->setPropertyFeatures($property, $validatedData['feature_ids']);
            // Gallery changes made in the form
            $this->removeGalleryImages($property, $validatedData['removed_image_ids'] ?? []);
            $this->addGalleryImages($property, $validatedData['propertyImages'] ?? []);
            //Update Property
            $property->update($validatedData);

            return redirect()->route('properties.index')->with('success', 'Property updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        if (!$property->exists()) {
            return Inertia::render('404');
        }
        $property->delete();
        return redirect()->route('properties.index')->with('success', 'Property deleted successfully.');
    }

    private function hasPropertyTitle($name)
    {
        return Property::where('name', $name)->exists();
    }

    private function setPropertyFeatures($property, $validatedData)
    {
        $property->features()->detach();
        $property->features()->attach($validatedData);
    }

    /** Stores newly cropped gallery photos (the gallery has no controller of its own; see ADMIN_UI.md §4). */
    private function addGalleryImages(Property $property, array $images): void
    {
        foreach ($images as $image) {
            $property->propertyImages()->create(['image' => $this->storeImage($image, 'property')]);
        }
    }

    /** Deletes the gallery photos removed in the form; scoped to this property so other ids are ignored. */
    private function removeGalleryImages(Property $property, array $imageIds): void
    {
        $property->propertyImages()->whereIn('id', $imageIds)->get()->each(function (PropertyImages $image) {
            Storage::delete($this->getRelativePath($image->image));
            $image->delete();
        });
    }
}
