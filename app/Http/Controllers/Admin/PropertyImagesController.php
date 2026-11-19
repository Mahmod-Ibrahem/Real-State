<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyImagesRequest;
use App\Http\Resources\PropertyImagesResource;
use App\Models\Property;
use App\Models\PropertyImages;
use App\Traits\ImagesUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PropertyImagesController extends Controller
{

    use ImagesUtility;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $propertyImages = PropertyImages::query()->where('property_id',\request('propertyId'))->select('image','id')->get();


        return Inertia::render('Admin/PropertyImages/Index', [
            'id' => \request('propertyId'),
            'propertyImages' => $propertyImages
         ]);
    }

    /**
     * Show the form for creating a new resource.
     */
//    public function create()
//    {
//        //
//    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PropertyImagesRequest $request)
    {
        $validatedData=$request->validated();
         foreach ($validatedData['images'] as $image) {
             PropertyImages::create([
                 'property_id' => $validatedData['property_id'],
                 'image' => $this->storeImage($image, 'property'),
             ]);
         }


        return redirect()->back()->with('success', 'Property Images created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PropertyImages $propertyImage)
    {
        $relativePath = $this->getRelativePath($propertyImage->image);
        Storage::delete($relativePath);
        $propertyImage->delete();
        return redirect()->back()->with('success', 'Property Image deleted successfully.');
    }
}
