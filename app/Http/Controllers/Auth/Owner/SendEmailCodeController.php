<?php

namespace App\Http\Controllers\Auth\Owner;

use App\Http\Controllers\Controller;
use App\Services\EmailVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class SendEmailCodeController extends Controller
{
    public function __construct(private readonly EmailVerificationService $verification)
    {
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $this->verification->sendCode($request->user());

        return back();
    }
}
