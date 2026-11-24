<?php

namespace App\Http\Controllers;

use App\Services\HomePageService;
use Inertia\Inertia;

class HomeController extends Controller
{

    public function index(HomePageService $homePageService)
    {

        $data = $homePageService->getHomePageData();
        return Inertia::render('Users/HomePage', $data);
    }
}
