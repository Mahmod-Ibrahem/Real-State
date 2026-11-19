<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class FeatureController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Users/Features', [
            'metaTitle' => 'Qeima Features | Smart Tools to Find, List & Manage Properties in Egypt',
            'metaDescription' => 'Discover powerful features on Qeima designed to simplify real estate in Egypt. From advanced search filters
              and interactive maps to secure messaging and listing management—explore everything that makes property finding easier',

        ]);
    }
}
