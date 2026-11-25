<?php

namespace Modules\Lead\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Property\Models\Property;
use Propaganistas\LaravelPhone\PhoneNumber;

class StorePropertyLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'property_id' => ['required', 'integer', Rule::exists(Property::class, 'id')],
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone_country' => ['required_with:phone', 'nullable', 'string', 'size:2'],
            'phone' => ['nullable', 'phone:phone_country'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.phone' => __('Enter a valid phone number for the selected country.'),
        ];
    }

    /** @return array{property_id: int, full_name: string, email: ?string, phone: ?string, message: ?string} */
    public function lead(): array
    {
        $phone = $this->validated('phone');

        return [
            ...$this->safe()->only(['property_id', 'full_name', 'email', 'message']),
            'phone' => $phone === null ? null : (new PhoneNumber($phone, $this->validated('phone_country')))->formatE164(),
        ];
    }
}
