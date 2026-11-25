<?php

namespace Modules\Lead\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Lead\Enums\LeadSource;

class ContactOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source' => ['required', Rule::enum(LeadSource::class)->only([LeadSource::Call, LeadSource::WhatsApp])],
        ];
    }

    public function source(): LeadSource
    {
        return LeadSource::from($this->validated('source'));
    }
}
