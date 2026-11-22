<?php

namespace App\Http\Controllers\Auth\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\OwnerRegistrationRequest;
use App\Services\OwnerRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class OwnerRegistrationController extends Controller
{
    public function __construct(private readonly OwnerRegistrationService $registration)
    {
    }

    public function create(): Response
    {
        return Inertia::render('Owner/Auth/Register');
    }

    public function store(OwnerRegistrationRequest $request): RedirectResponse
    {
        Auth::login($this->registration->register($request->validated()));

        return to_route('owner.dashboard');
    }
}
