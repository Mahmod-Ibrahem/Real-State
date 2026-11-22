<?php

namespace Modules\Property\Services;

use App\Models\User;
use Modules\Property\Enums\ReviewStatus;
use Modules\Property\Events\PropertyApproved;
use Modules\Property\Exceptions\PropertyAlreadyApproved;
use Modules\Property\Models\Property;

final class PropertyReviewService
{
    public function approve(Property $property, User $reviewer): void
    {
        if ($property->isApproved()) {
            throw PropertyAlreadyApproved::for($property);
        }

        $property->forceFill([
            'review_status' => ReviewStatus::Approved,
            'rejection_reason' => null,
            ...$this->reviewedBy($reviewer),
        ])->save();

        PropertyApproved::dispatch($property);
    }

    public function reject(Property $property, User $reviewer, string $reason): void
    {
        $property->forceFill([
            'review_status' => ReviewStatus::Rejected,
            'rejection_reason' => $reason,
            'is_published' => false,
            ...$this->reviewedBy($reviewer),
        ])->save();
    }

    public function returnToReview(Property $property): void
    {
        $property->forceFill([
            'review_status' => ReviewStatus::Pending,
            'rejection_reason' => null,
            'is_published' => false,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ])->save();
    }

    private function reviewedBy(User $reviewer): array
    {
        return ['reviewed_by' => $reviewer->id, 'reviewed_at' => now()];
    }
}
