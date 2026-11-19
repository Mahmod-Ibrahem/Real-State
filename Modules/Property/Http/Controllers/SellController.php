<?php

namespace Modules\Property\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class SellController extends Controller
{
    /**
     * "List your property" landing page. It shows no listings, so no query is needed.
     */
    public function index()
    {
        return Inertia::render('Users/Sell',
            [
                'metaTitle' => 'Sell Your Property in Egypt | List Apartments, Villas & More – Qeima',
                'metaDescription' => 'Easily sell your property in Egypt with Qeima. List your apartment, villa, or commercial space and reach thousands of serious buyers. Fast, secure, and hassle-free property selling across Egypt. Start listing now!',
            ]);
    }
}
