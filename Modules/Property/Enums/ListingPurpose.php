<?php

namespace Modules\Property\Enums;

/**
 * Why a property is listed. Fixed by the code (Buy / Rent pages, search tabs),
 * so it lives in an enum instead of an admin-editable table.
 */
enum ListingPurpose: string
{
    case Sale = 'sale';
    case Rent = 'rent';

    public function label(): string
    {
        return match ($this) {
            self::Sale => __('For Sale'),
            self::Rent => __('For Rent'),
        };
    }

    public function allowsPaymentPlans(): bool
    {
        return $this === self::Sale;
    }

    /** Off-plan units are only sold; a rental is always ready to move. */
    public function allowsDeliveryDate(): bool
    {
        return $this === self::Sale;
    }

    /**
     * Options for selects, tabs and filters: [['id' => 'sale', 'name' => 'For Sale'], ...]
     */
    public static function options(): array
    {
        return array_map(
            fn (self $purpose) => ['id' => $purpose->value, 'name' => $purpose->label()],
            self::cases()
        );
    }
}
