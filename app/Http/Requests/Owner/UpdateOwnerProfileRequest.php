<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;
use Propaganistas\LaravelPhone\PhoneNumber;

class UpdateOwnerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'whatsapp_country' => ['required_with:whatsapp_phone', 'nullable', 'string', 'size:2'],
            'whatsapp_phone' => ['nullable', 'phone:whatsapp_country,mobile'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_avatar' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'whatsapp_phone.phone' => __('Enter a valid mobile number for the selected country.'),
        ];
    }

    /** The WhatsApp number in E.164 (both null to remove it), plus the avatar fields as sent. */
    public function profile(): array
    {
        $phone = $this->validated('whatsapp_phone');

        return [
            ...$this->safe()->only(['avatar', 'remove_avatar']),
            'whatsapp_phone' => $phone === null ? null : (new PhoneNumber($phone, $this->validated('whatsapp_country')))->formatE164(),
            'whatsapp_country' => $phone === null ? null : $this->validated('whatsapp_country'),
        ];
    }
}
