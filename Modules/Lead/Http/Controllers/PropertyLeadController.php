<?php

namespace Modules\Lead\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Lead\Http\Requests\SubmitPropertyLeadRequest;
use Modules\Lead\Services\PropertyLeadService;

final class PropertyLeadController extends Controller
{
    public function __construct(private readonly PropertyLeadService $leads)
    {
    }

    public function store(SubmitPropertyLeadRequest $request, string $listing): RedirectResponse
    {
        $this->leads->submit($listing, $request->lead());

        return back()->with('message', __('Your message was sent to the owner.'));
    }
}
