<?php

namespace Modules\Lead\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Lead\Enums\LeadSource;
use Modules\Lead\Enums\LeadStatus;
use Modules\Lead\Http\Requests\IndexPropertyLeadRequest;
use Modules\Lead\Http\Requests\Owner\StoreOwnerPropertyLeadRequest;
use Modules\Lead\Http\Requests\UpdatePropertyLeadRequest;
use Modules\Lead\Http\Resources\LeadBoardColumnResource;
use Modules\Lead\Http\Resources\LeadPropertyOptionResource;
use Modules\Lead\Http\Resources\PropertyLeadListResource;
use Modules\Lead\Models\PropertyLead;
use Modules\Lead\Services\PropertyLeadService;

final class PropertyLeadController extends Controller
{
    public function __construct(private readonly PropertyLeadService $leads)
    {
    }

    public function index(IndexPropertyLeadRequest $request): Response
    {
        $owner = $request->user();

        return Inertia::render('Owner/Leads/Index', [
            'board' => $request->isBoard()
                ? LeadBoardColumnResource::collection($this->leads->board($owner, $request->filters()))
                : null,
            'leads' => $request->isBoard()
                ? null
                : PropertyLeadListResource::collection($this->leads->paginate($owner, $request->filters())),
            'filters' => $request->selected(),
            'statuses' => LeadStatus::options(),
            'sources' => LeadSource::options(),
            'properties' => LeadPropertyOptionResource::collection($this->leads->propertyOptions($owner)),
        ]);
    }

    public function store(StoreOwnerPropertyLeadRequest $request): RedirectResponse
    {
        $this->leads->add($request->lead());

        return back();
    }

    public function update(UpdatePropertyLeadRequest $request, PropertyLead $propertyLead): RedirectResponse
    {
        $this->leads->updateContact($propertyLead, $request->contact());

        return back();
    }
}
