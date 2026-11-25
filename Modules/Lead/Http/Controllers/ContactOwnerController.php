<?php

namespace Modules\Lead\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Lead\Http\Requests\ContactOwnerRequest;
use Modules\Lead\Services\PropertyLeadService;

final class ContactOwnerController extends Controller
{
    public function __construct(private readonly PropertyLeadService $leads)
    {
    }

    public function __invoke(ContactOwnerRequest $request, string $listing): RedirectResponse
    {
        return redirect()->away($this->leads->contact($listing, $request->source()));
    }
}
