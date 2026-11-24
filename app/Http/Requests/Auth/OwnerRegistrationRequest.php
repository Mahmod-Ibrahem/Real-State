<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Propaganistas\LaravelPhone\PhoneNumber;

class OwnerRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled(['phone', 'phone_country'])) {
            return;
        }

        // Store and compare one format (E.164), however the number was typed
        $e164 = rescue(fn () => (new PhoneNumber($this->input('phone'), $this->input('phone_country')))->formatE164(), report: false);

        if ($e164 !== null) {
            $this->merge(['phone' => $e164]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'phone_country' => ['required', 'string', 'size:2'],
            'phone' => ['required', 'phone:phone_country,mobile', Rule::unique(User::class)],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.phone' => __('Enter a valid mobile number for the selected country.'),
            'phone.unique' => __('This phone number is already registered.'),
        ];
    }
}
