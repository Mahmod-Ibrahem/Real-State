<?php

namespace App\Http\Controllers;

use App\Http\Resources\AreaResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\Users\propertyResource;
use App\Http\Resources\Users\propertyTypeResource;
use App\Models\Area;
use App\Models\BasicContent;
use App\Models\Broker;
use App\Models\City;
use App\Models\Country;
use App\Models\Property;
use App\Models\PropertyClassifications;
use App\Models\PropertyType;
use App\Services\HomePageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;

class HomeController extends Controller
{

    public function index(HomePageService $homePageService)
    {

        $data = $homePageService->getHomePageData();
        return Inertia::render('Users/HomePage', $data);
    }
}
