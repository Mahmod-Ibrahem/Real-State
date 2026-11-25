<?php

namespace Modules\Lead\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Propaganistas\LaravelPhone\PhoneNumber;

class SubmitPropertyLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:100'],
            'phone_country' => ['required', 'string', 'size:2'],
            'phone' => ['required', 'phone:phone_country'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.phone' => __('Enter a valid phone number for the selected country.'),
        ];
    }

    /** @return array{full_name: string, phone: string, email: string, message: ?string} */
    public function lead(): array
    {
        return [
            ...$this->safe()->only(['full_name', 'email', 'message']),
            'phone' => (new PhoneNumber($this->validated('phone'), $this->validated('phone_country')))->formatE164(),
        ];
    }
}
