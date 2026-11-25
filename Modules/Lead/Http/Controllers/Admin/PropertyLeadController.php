<?php

namespace Modules\Lead\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Lead\Enums\LeadSource;
use Modules\Lead\Enums\LeadStatus;
use Modules\Lead\Http\Requests\IndexPropertyLeadRequest;
use Modules\Lead\Http\Requests\StorePropertyLeadRequest;
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
        return Inertia::render('Admin/PropertyLeads/Index', [
            'board' => $request->isBoard()
                ? LeadBoardColumnResource::collection($this->leads->board(null, $request->filters()))
                : null,
            'leads' => $request->isBoard()
                ? null
                : PropertyLeadListResource::collection($this->leads->paginate(null, $request->filters())),
            'filters' => $request->selected(),
            'statuses' => LeadStatus::options(),
            'sources' => LeadSource::options(),
            'properties' => LeadPropertyOptionResource::collection($this->leads->propertyOptions(null)),
        ]);
    }

    public function store(StorePropertyLeadRequest $request): RedirectResponse
    {
        $this->leads->add($request->lead());

        return back();
    }

    public function update(UpdatePropertyLeadRequest $request, PropertyLead $propertyLead): RedirectResponse
    {
        $this->leads->updateContact($propertyLead, $request->contact());

        return back();
    }

    public function destroy(PropertyLead $propertyLead): RedirectResponse
    {
        $this->leads->delete($propertyLead);

        return back();
    }
}
