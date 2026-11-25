<?php

namespace Modules\Property\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Property\Enums\ListingPurpose;
use Modules\Property\Enums\PaymentType;

class IndexListingRequest extends FormRequest
{
    private const MAX_PERCENT = 100;

    public function authorize(): bool
    {
        return true;
    }

    /** Bad filter values in a shared URL are dropped by filters(), never shown as errors. */
    public function rules(): array
    {
        return [];
    }

    /**
     * Normalised filters; ids and amounts are ints so the sidebar fields match the URL.
     *
     * @return array{search: ?string, purpose: ?string, type: ?int, city: ?int, area: ?int, classification: ?int,
     *     country: ?int, payment: ?PaymentType, max_monthly: ?int, max_down_payment_percent: ?int}
     */
    public function filters(): array
    {
        $positive = fn (string $key) => $this->integer($key) > 0 ? $this->integer($key) : null;
        $percent = filter_var($this->query('max_down_payment_percent'), FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => self::MAX_PERCENT],
        ]);

        return [
            'search' => $this->string('search')->trim()->toString() ?: null,
            'purpose' => ListingPurpose::tryFrom((string) $this->query('purpose'))?->value,
            'type' => $positive('type'),
            'city' => $positive('city'),
            'area' => $positive('area'),
            'classification' => $positive('classification'),
            'country' => $positive('country'),
            'payment' => PaymentType::tryFrom((string) $this->query('payment')),
            'max_monthly' => $positive('max_monthly'),
            'max_down_payment_percent' => $percent === false ? null : $percent,
        ];
    }
}
