<?php

namespace App\Http\Controllers;

use Modules\Location\Http\Resources\AreaResource;
use Modules\Location\Http\Resources\CityResource;
use Modules\Location\Http\Resources\CountryResource;
use Modules\Property\Http\Resources\Users\PropertyResource;
use Modules\Property\Http\Resources\Users\PropertyTypeResource;
use Modules\Location\Models\Area;
use Modules\Content\Models\BasicContent;
use Modules\Content\Models\Broker;
use Modules\Location\Models\City;
use Modules\Location\Models\Country;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyClassifications;
use Modules\Property\Models\PropertyType;
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
