<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\UpdateOwnerProfileRequest;
use App\Http\Resources\OwnerProfileResource;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class OwnerProfileController extends Controller
{
    public function __construct(private readonly ProfileService $profiles)
    {
    }

    public function show(Request $request): Response
    {
        return Inertia::render('Owner/Profile/Show', [
            'profile' => OwnerProfileResource::make($request->user()),
        ]);
    }

    public function update(UpdateOwnerProfileRequest $request): RedirectResponse
    {
        $this->profiles->update($request->user(), $request->profile());

        return back();
    }
}
