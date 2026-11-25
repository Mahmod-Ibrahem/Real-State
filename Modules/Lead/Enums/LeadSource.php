<?php

namespace Modules\Lead\Enums;

enum LeadSource: string
{
    case Form = 'form';
    case Call = 'call';
    case WhatsApp = 'whatsapp';

    public function label(): string
    {
        return match ($this) {
            self::Form => __('Enquiry form'),
            self::Call => __('Call'),
            self::WhatsApp => __('WhatsApp'),
        };
    }

    public function revealsOwnerPhone(): bool
    {
        return $this !== self::Form;
    }

    /** @return list<array{id: string, name: string}> */
    public static function options(): array
    {
        return array_map(fn (self $source) => ['id' => $source->value, 'name' => $source->label()], self::cases());
    }
}
