<?php

namespace Modules\Dashboard\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Dashboard\Http\Resources\RecentContactLeadResource;
use Modules\Dashboard\Http\Resources\RecentPropertyLeadResource;
use Modules\Dashboard\Services\DashboardService;

final class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboard)
    {
    }

    public function __invoke(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'listings' => $this->dashboard->listings(),
            'leads' => $this->dashboard->leadsThisMonth(),
            'catalog' => $this->dashboard->catalog(),
            'recent_property_leads' => RecentPropertyLeadResource::collection($this->dashboard->recentPropertyLeads()),
            'recent_contact_leads' => RecentContactLeadResource::collection($this->dashboard->recentContactLeads()),
        ]);
    }
}
