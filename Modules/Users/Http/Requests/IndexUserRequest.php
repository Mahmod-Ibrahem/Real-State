<?php

namespace Modules\Users\Http\Requests;

use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Users\Enums\AccountReview;
use Modules\Users\Enums\AccountStatus;
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
            'status' => ['nullable', Rule::enum(AccountStatus::class)],
            'review' => ['nullable', Rule::enum(AccountReview::class)],
        ];
    }

    /** @return array{search: ?string, role_id: ?int, status: ?AccountStatus, review: ?AccountReview} */
    public function filters(): array
    {
        return [
            'search' => $this->string('search')->trim()->toString() ?: null,
            'role_id' => $this->filled('role') ? $this->integer('role') : null,
            'status' => $this->enum('status', AccountStatus::class),
            'review' => $this->enum('review', AccountReview::class),
        ];
    }

    public function activeFilters(): array
    {
        $filters = $this->filters();

        return [
            'role' => $filters['role_id'],
            'status' => $filters['status']?->value,
            'review' => $filters['review']?->value,
        ];
    }
}
