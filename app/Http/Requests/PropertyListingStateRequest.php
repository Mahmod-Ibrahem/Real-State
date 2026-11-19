<?php

namespace App\Http\Requests;

use App\Enums\ListingPurpose;
use App\Enums\PropertyStatus;
use App\Models\Property;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Quick edits from the properties table: any one of visibility, purpose or status. */
class PropertyListingStateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Property $property */
        $property = $this->route('property');
        $purpose = ListingPurpose::tryFrom((string) $this->input('purpose')) ?? $property->purpose;

        return [
            'is_published' => ['sometimes', 'boolean'],
            'purpose' => ['sometimes', Rule::enum(ListingPurpose::class)],
            // Sold only for sale listings, Rented only for rent listings
            'status' => ['sometimes', Rule::enum(PropertyStatus::class)->only(PropertyStatus::allowedFor($purpose))],
        ];
    }

    public function messages(): array
    {
        return [
            'status.Illuminate\Validation\Rules\Enum' => 'This status does not match the listing purpose (Sold is for sale listings, Rented is for rent listings).',
        ];
    }
}
