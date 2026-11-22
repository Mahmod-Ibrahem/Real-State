<?php

namespace Modules\Property\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Property\Models\Property;
use Modules\Property\Services\PropertyPublicationService;

final class PublishPropertyController extends Controller
{
    public function __construct(private readonly PropertyPublicationService $publication)
    {
    }

    public function __invoke(Property $property): RedirectResponse
    {
        $this->publication->publish($property);

        return back();
    }
}
