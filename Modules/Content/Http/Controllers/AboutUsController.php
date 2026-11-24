<?php

namespace Modules\Content\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class AboutUsController extends Controller
{
    public function __invoke()
    {
        $aboutVideos = \Modules\Content\Models\BasicContent::where('type', 'about_video')->first();

        return Inertia::render('Users/AboutUs',
            [
                'metaTitle' => 'About Qeima | Egypt’s Leading Real Estate Platform for Buying, Selling & Renting',
                'metaDescription' => 'Learn more about Qeima – your trusted real estate partner in Egypt. Our mission is to simplify property search and connect buyers,
              sellers, and renters with verified listings across the country. Discover our story and values',
                'aboutVideo' => $aboutVideos->only('content'),
            ]
        );
    }
}
