<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function changeLocale(Request $request)
    {
        $data=$request->validate([
            'locale' => 'required|in:en,ar',
        ]);
        session()->put('locale', $data['locale']);
        app()->setLocale($data['locale']);
        return redirect()->back();
    }
}
