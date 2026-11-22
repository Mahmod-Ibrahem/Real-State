<?php

namespace Modules\Property\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Property\Enums\ReviewStatus;

class IndexPropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'review' => ['nullable', Rule::enum(ReviewStatus::class)],
        ];
    }

    public function search(): ?string
    {
        return $this->string('search')->trim()->toString() ?: null;
    }

    public function reviewStatus(): ?ReviewStatus
    {
        return $this->enum('review', ReviewStatus::class);
    }
}
