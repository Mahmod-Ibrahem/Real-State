<?php

namespace Modules\Property\Enums;

enum InstallmentFrequency: string
{
    case Monthly = 'monthly';
    case Quarterly = 'quarterly';

    public function label(): string
    {
        return match ($this) {
            self::Monthly => __('Monthly'),
            self::Quarterly => __('Quarterly'),
        };
    }

    public function paymentsPerYear(): int
    {
        return match ($this) {
            self::Monthly => 12,
            self::Quarterly => 4,
        };
    }

    /** @return list<array{id: string, name: string}> */
    public static function options(): array
    {
        return array_map(fn (self $frequency) => ['id' => $frequency->value, 'name' => $frequency->label()], self::cases());
    }
}
