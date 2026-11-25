<?php

namespace Modules\Property\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Lead\Services\PropertyLeadService;
use Modules\Property\Services\OwnerPropertyService;

final class DashboardController extends Controller
{
    public function __construct(
        private readonly OwnerPropertyService $properties,
        private readonly NotificationService $notifications,
        private readonly PropertyLeadService $leads,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        return Inertia::render('Owner/Dashboard', [
            'counts' => $this->properties->countsFor($request->user()),
            'new_leads' => $this->leads->newCountFor($request->user()),
            'notifications' => NotificationResource::collection($this->notifications->latestFor($request->user())),
            'under_review' => $request->user()->under_review,
            'unverified_email' => ! $request->user()->hasVerifiedEmail(),
            'unverified_phone' => $request->user()->phone !== null && ! $request->user()->hasVerifiedPhone(),
        ]);
    }
}
