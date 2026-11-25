<?php

namespace Modules\Lead\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Lead\Http\Requests\PropertyLeadStatusRequest;
use Modules\Lead\Models\PropertyLead;
use Modules\Lead\Services\PropertyLeadService;

final class PropertyLeadStatusController extends Controller
{
    public function __construct(private readonly PropertyLeadService $leads)
    {
    }

    public function __invoke(PropertyLeadStatusRequest $request, PropertyLead $propertyLead): RedirectResponse
    {
        $this->leads->updateStatus($propertyLead, $request->status());

        return back();
    }
}
