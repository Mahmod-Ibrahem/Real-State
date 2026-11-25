<?php

namespace Modules\Property\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Modules\Property\Models\Property;

final class OwnerPageService
{
    private const PER_PAGE = 10;

    public function withListedCount(User $owner): User
    {
        return $owner->loadCount(['properties as listed_properties_count' => fn (Builder $properties) => $properties->active()]);
    }

    public function paginateListingsOf(User $owner): LengthAwarePaginator
    {
        return Property::query()
            ->active()
            ->ownedBy($owner)
            ->with(['propertyType', 'paymentPlans'])
            ->latest()
            ->paginate(self::PER_PAGE)
            ->withQueryString();
    }
}
