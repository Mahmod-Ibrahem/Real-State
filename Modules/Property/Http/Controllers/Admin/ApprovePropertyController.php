<?php

namespace Modules\Property\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Property\Models\Property;
use Modules\Property\Services\PropertyReviewService;

final class ApprovePropertyController extends Controller
{
    public function __construct(private readonly PropertyReviewService $review)
    {
    }

    public function __invoke(Request $request, Property $property): RedirectResponse
    {
        $this->review->approve($property, $request->user());

        return back();
    }
}
