<?php

namespace Modules\Property\Enums;

/**
 * Availability of a property. Sold only applies to sale listings, Rented only to rent listings.
 */
enum PropertyStatus: string
{
    case Available = 'available';
    case Sold = 'sold';
    case Rented = 'rented';

    public function label(): string
    {
        return match ($this) {
            self::Available => __('Available'),
            self::Sold => __('Sold'),
            self::Rented => __('Rented'),
        };
    }

    /** @return self[] statuses that make sense for the given purpose */
    public static function allowedFor(?ListingPurpose $purpose): array
    {
        return match ($purpose) {
            ListingPurpose::Sale => [self::Available, self::Sold],
            ListingPurpose::Rent => [self::Available, self::Rented],
            null => self::cases(),
        };
    }

    /** The closed status for a purpose (Sold for sale, Rented for rent). */
    public static function closedFor(ListingPurpose $purpose): self
    {
        return $purpose === ListingPurpose::Sale ? self::Sold : self::Rented;
    }

    /**
     * Options for the admin select: [['id' => 'sold', 'name' => 'Sold', 'purposes' => ['sale']], ...]
     */
    public static function options(): array
    {
        return array_map(fn (self $status) => [
            'id' => $status->value,
            'name' => $status->label(),
            'purposes' => array_values(array_map(
                fn (ListingPurpose $purpose) => $purpose->value,
                array_filter(ListingPurpose::cases(), fn (ListingPurpose $purpose) => in_array($status, self::allowedFor($purpose), true))
            )),
        ], self::cases());
    }

    /** Shape sent to the frontend for badges: null while available. */
    public function badge(): ?array
    {
        return $this === self::Available ? null : ['value' => $this->value, 'label' => $this->label()];
    }
}
