<?php

namespace App\Http\Controllers\Auth\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyPhoneCodeRequest;
use App\Services\PhoneVerification\PhoneVerificationService;
use Illuminate\Http\RedirectResponse;

final class VerifyPhoneCodeController extends Controller
{
    public function __construct(private readonly PhoneVerificationService $verification)
    {
    }

    public function __invoke(VerifyPhoneCodeRequest $request): RedirectResponse
    {
        $this->verification->confirm($request->user(), $request->code());

        return back();
    }
}
