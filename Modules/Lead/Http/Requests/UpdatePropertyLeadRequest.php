<?php

namespace Modules\Lead\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Propaganistas\LaravelPhone\PhoneNumber;

class UpdatePropertyLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone_country' => ['required_with:phone', 'nullable', 'string', 'size:2'],
            'phone' => ['nullable', 'phone:phone_country'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.phone' => __('Enter a valid phone number for the selected country.'),
        ];
    }

    /** @return array{full_name: ?string, email: ?string, phone: ?string} */
    public function contact(): array
    {
        $phone = $this->validated('phone');

        return [
            'full_name' => $this->validated('full_name'),
            'email' => $this->validated('email'),
            'phone' => $phone === null ? null : (new PhoneNumber($phone, $this->validated('phone_country')))->formatE164(),
        ];
    }
}
