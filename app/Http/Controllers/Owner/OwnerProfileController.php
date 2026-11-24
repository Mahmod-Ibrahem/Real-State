<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\OwnerProfileResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class OwnerProfileController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Owner/Profile/Show', [
            'profile' => OwnerProfileResource::make($request->user()),
        ]);
    }
}
