<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string',
        ];

        if ($this->isMethod('POST')) {
            $rules['image'] = 'required|mimes:jpeg,png,jpg,webp,avif|max:2048';
        } else {
            // Editing keeps the current image unless a new one is uploaded
            $rules['image'] = 'nullable|mimes:jpeg,png,jpg,webp,avif|max:2048';
        }

        return $rules;
    }
}
