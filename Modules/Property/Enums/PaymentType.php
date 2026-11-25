<?php

namespace Modules\Property\Enums;

enum PaymentType: string
{
    case Cash = 'cash';
    case Installments = 'installments';

    public function label(): string
    {
        return match ($this) {
            self::Cash => __('Cash'),
            self::Installments => __('Installments'),
        };
    }

    /** @return list<array{id: string, name: string}> */
    public static function options(): array
    {
        return array_map(fn (self $type) => ['id' => $type->value, 'name' => $type->label()], self::cases());
    }
}
