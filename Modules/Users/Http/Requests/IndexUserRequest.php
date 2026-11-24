<?php

namespace Modules\Users\Http\Requests;

use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class IndexUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'role' => [
                'nullable', 'integer',
                Rule::exists(Role::class, 'id')->whereNot('name', PermissionSeeder::SUPER_ADMIN),
            ],
        ];
    }

    public function search(): ?string
    {
        return $this->string('search')->trim()->toString() ?: null;
    }

    public function roleId(): ?int
    {
        return $this->filled('role') ? $this->integer('role') : null;
    }
}
