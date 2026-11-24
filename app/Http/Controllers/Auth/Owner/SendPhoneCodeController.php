<?php

namespace App\Http\Controllers\Auth\Owner;

use App\Http\Controllers\Controller;
use App\Services\PhoneVerification\PhoneVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class SendPhoneCodeController extends Controller
{
    public function __construct(private readonly PhoneVerificationService $verification)
    {
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $this->verification->sendCode($request->user());

        return back();
    }
}
