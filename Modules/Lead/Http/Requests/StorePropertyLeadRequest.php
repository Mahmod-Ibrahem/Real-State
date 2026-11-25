<?php

namespace Modules\Lead\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Lead\Enums\LeadSource;
use Propaganistas\LaravelPhone\PhoneNumber;

class StorePropertyLeadRequest extends FormRequest
{
    private const DEFAULT_PHONE_COUNTRY = 'EG';

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('phone')) {
            return;
        }

        // Visitors type a local Egyptian number or an international one; both are stored as E.164
        $e164 = rescue(fn () => (new PhoneNumber($this->input('phone'), self::DEFAULT_PHONE_COUNTRY))->formatE164(), report: false);

        if ($e164 !== null) {
            $this->merge(['phone' => $e164]);
        }
    }

    public function rules(): array
    {
        return [
            'source' => ['required', Rule::enum(LeadSource::class)],
            'full_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'phone:INTERNATIONAL,' . self::DEFAULT_PHONE_COUNTRY],
            'email' => [Rule::requiredIf(fn () => $this->source() === LeadSource::Form), 'nullable', 'email', 'max:255'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.phone' => __('Enter a valid phone number.'),
        ];
    }

    public function source(): ?LeadSource
    {
        return LeadSource::tryFrom((string) $this->input('source'));
    }

    /** @return array{full_name: string, phone: string, email: ?string, message: ?string} */
    public function lead(): array
    {
        return $this->safe()->only(['full_name', 'phone', 'email', 'message']);
    }
}
