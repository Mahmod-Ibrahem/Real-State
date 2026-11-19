<?php

namespace App\Http\Controllers;

use App\Models\Broker;
use App\Services\BrokerService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutUsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    protected function __construct(protected BrokerService $brokerService)
    {
    }

    public function __invoke()
    {
        $aboutVideos = \App\Models\BasicContent::where('type', 'about_video')->first();
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
