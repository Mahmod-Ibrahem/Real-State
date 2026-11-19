<?php

namespace Modules\Content\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Modules\Content\Services\BrokerService;

class AboutUsController extends Controller
{
    public function __construct(protected BrokerService $brokerService)
    {
    }

    public function __invoke()
    {
        $aboutVideos = \Modules\Content\Models\BasicContent::where('type', 'about_video')->first();
        $brokers = $this->brokerService->all();

        return Inertia::render('Users/AboutUs',
            [
                'metaTitle' => 'About Qeima | Egypt’s Leading Real Estate Platform for Buying, Selling & Renting',
                'metaDescription' => 'Learn more about Qeima – your trusted real estate partner in Egypt. Our mission is to simplify property search and connect buyers,
              sellers, and renters with verified listings across the country. Discover our story and values',
                'aboutVideo' => $aboutVideos->only('content'),
                'brokers' => $brokers
            ]
        );
    }
}
