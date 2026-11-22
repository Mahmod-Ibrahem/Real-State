<?php

namespace App\Http\Controllers\Auth\Owner;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

final class OwnerSessionController extends AuthenticatedSessionController
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'title' => __('Property owner login'),
            'loginRoute' => 'owner.login',
            'registerRoute' => 'owner.register',
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }
}
