<?php

namespace App\Http\Controllers\Auth\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailCodeRequest;
use App\Services\EmailVerification\EmailVerificationService;
use Illuminate\Http\RedirectResponse;

final class VerifyEmailCodeController extends Controller
{
    public function __construct(private readonly EmailVerificationService $verification)
    {
    }

    public function __invoke(VerifyEmailCodeRequest $request): RedirectResponse
    {
        $this->verification->confirm($request->user(), $request->code());

        return back();
    }
}
