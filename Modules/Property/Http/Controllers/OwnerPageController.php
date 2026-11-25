<?php

namespace Modules\Property\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Property\Http\Resources\Users\OwnerResource;
use Modules\Property\Http\Resources\Users\PropertyResource;
use Modules\Property\Services\OwnerPageService;

final class OwnerPageController extends Controller
{
    public function __construct(private readonly OwnerPageService $owners)
    {
    }

    public function __invoke(User $owner): Response
    {
        return Inertia::render('Users/OwnerProfile', [
            'owner' => OwnerResource::make($this->owners->withListedCount($owner)),
            'properties' => PropertyResource::collection($this->owners->paginateListingsOf($owner)),
        ]);
    }
}
