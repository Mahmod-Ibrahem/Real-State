<?php

namespace Modules\Lead\Enums;

enum LeadStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::New => __('New'),
            self::Contacted => __('Contacted'),
            self::Closed => __('Closed'),
        };
    }

    public function tone(): string
    {
        return match ($this) {
            self::New => 'info',
            self::Contacted => 'warning',
            self::Closed => 'success',
        };
    }

    /** @return list<array{id: string, name: string, tone: string}> */
    public static function options(): array
    {
        return array_map(
            fn (self $status) => ['id' => $status->value, 'name' => $status->label(), 'tone' => $status->tone()],
            self::cases(),
        );
    }
}
