<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /** @return array{email: string, password: string} */
    public function credentials(): array
    {
        return $this->only('email', 'password');
    }

    public function remember(): bool
    {
        return $this->boolean('remember');
    }
}
