<?php

namespace Modules\Property\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Property\Enums\ReviewStatus;
use Modules\Property\Events\PropertySubmitted;
use Modules\Property\Models\Property;

final class OwnerPropertyService
{
    private const PER_PAGE = 10;

    public function __construct(
        private readonly PropertyService $properties,
        private readonly PropertyReviewService $review,
    ) {
    }

    public function paginateFor(User $owner): LengthAwarePaginator
    {
        return Property::query()
            ->ownedBy($owner)
            ->with(['propertyType', 'city', 'area'])
            ->latest()
            ->paginate(self::PER_PAGE)
            ->withQueryString();
    }

    /** @return array<string, int> */
    public function countsFor(User $owner): array
    {
        $byStatus = Property::query()->ownedBy($owner)
            ->selectRaw('review_status, count(*) as total')
            ->groupBy('review_status')
            ->toBase()
            ->pluck('total', 'review_status');

        return [
            ...collect(ReviewStatus::cases())
                ->mapWithKeys(fn (ReviewStatus $status) => [$status->value => (int) ($byStatus[$status->value] ?? 0)])
                ->all(),
            'published' => Property::query()->ownedBy($owner)->active()->count(),
        ];
    }

    public function submit(User $owner, array $data): Property
    {
        $property = $this->properties->create($data, $owner, ReviewStatus::Pending);

        PropertySubmitted::dispatch($property);

        return $property;
    }

    public function update(Property $property, array $data): Property
    {
        $wasReviewed = $property->review_status !== ReviewStatus::Pending;

        DB::transaction(function () use ($property, $data, $wasReviewed): void {
            $this->properties->update($property, $data);

            if ($wasReviewed) {
                $this->review->returnToReview($property);
            }
        });

        if ($wasReviewed) {
            PropertySubmitted::dispatch($property);
        }

        return $property;
    }
}
