<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrokerRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string'],
            'facebook' => ['nullable', 'string'],
            'instagram' => ['nullable', 'string'],
            'twitter' => ['nullable', 'string'],
            // Required when adding a broker; editing keeps the saved photo unless a new one is sent
            'image' => [$this->isMethod('POST') ? 'required' : 'nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,avif,webp', 'max:2048'],
        ];
    }
}
