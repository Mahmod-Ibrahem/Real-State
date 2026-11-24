<?php

namespace Modules\Users\Http\Requests;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role_id' => [
                'required', 'integer',
                Rule::exists(Role::class, 'id')->whereNot('name', PermissionSeeder::SUPER_ADMIN),
            ],
        ];
    }
}
