<?php

namespace Modules\Property\Enums;

enum ReviewStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('Under review'),
            self::Approved => __('Approved'),
            self::Rejected => __('Rejected'),
        };
    }

    public function tone(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn (self $status) => ['id' => $status->value, 'name' => $status->label(), 'tone' => $status->tone()],
            self::cases()
        );
    }
}
