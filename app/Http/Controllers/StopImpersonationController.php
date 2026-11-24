<?php

namespace App\Http\Controllers;

use App\Services\ImpersonationService;
use Illuminate\Http\RedirectResponse;

final class StopImpersonationController extends Controller
{
    public function __construct(private readonly ImpersonationService $impersonation)
    {
    }

    public function __invoke(): RedirectResponse
    {
        $this->impersonation->stop();

        return to_route('admin.users.index');
    }
}
