<?php

namespace Modules\Property\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Property\Http\Requests\RejectPropertyRequest;
use Modules\Property\Models\Property;
use Modules\Property\Services\PropertyReviewService;

final class RejectPropertyController extends Controller
{
    public function __construct(private readonly PropertyReviewService $review)
    {
    }

    public function __invoke(RejectPropertyRequest $request, Property $property): RedirectResponse
    {
        $this->review->reject($property, $request->user(), $request->reason());

        return back();
    }
}
