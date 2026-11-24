<?php

namespace App\Http\Middleware;

use App\Exceptions\ActionBlockedWhileImpersonating;
use App\Services\ImpersonationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class PreventWhileImpersonating
{
    public function __construct(private readonly ImpersonationService $impersonation)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->impersonation->isImpersonating()) {
            throw ActionBlockedWhileImpersonating::make();
        }

        return $next($request);
    }
}
