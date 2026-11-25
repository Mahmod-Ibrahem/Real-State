<?php

namespace Modules\Lead\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Lead\Http\Requests\StorePropertyLeadRequest;
use Modules\Lead\Services\PropertyLeadService;

final class PropertyLeadController extends Controller
{
    public function __construct(private readonly PropertyLeadService $leads)
    {
    }

    public function store(StorePropertyLeadRequest $request, string $listing): RedirectResponse
    {
        $lead = $this->leads->submit($listing, $request->lead(), $request->source());

        return back()
            ->with('message', __('Your message was sent to the owner.'))
            ->with('contact_url', $this->leads->contactLinkFor($lead));
    }
}
