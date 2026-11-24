<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Quick edits from the users table: whether the account is active, and whether it is under review. */
class UserAccountStateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_active' => ['sometimes', 'boolean'],
            'under_review' => ['sometimes', 'boolean'],
        ];
    }
}
