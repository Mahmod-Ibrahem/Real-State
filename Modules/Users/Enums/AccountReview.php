<?php

namespace Modules\Users\Enums;

enum AccountReview: string
{
    case UnderReview = 'under_review';
    case Approved = 'approved';

    public static function of(bool $isUnderReview): self
    {
        return $isUnderReview ? self::UnderReview : self::Approved;
    }

    public function isUnderReview(): bool
    {
        return $this === self::UnderReview;
    }

    public function label(): string
    {
        return match ($this) {
            self::UnderReview => __('Under review'),
            self::Approved => __('Approved'),
        };
    }

    public function tone(): string
    {
        return match ($this) {
            self::UnderReview => 'warning',
            self::Approved => 'success',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn (self $review) => ['id' => $review->value, 'name' => $review->label(), 'tone' => $review->tone()],
            self::cases()
        );
    }
}
