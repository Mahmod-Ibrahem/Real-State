<?php

namespace Modules\Lead\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Lead\Enums\LeadSource;
use Modules\Lead\Enums\LeadStatus;

class IndexPropertyLeadRequest extends FormRequest
{
    public const BOARD = 'board';
    public const TABLE = 'table';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'view' => ['nullable', Rule::in([self::BOARD, self::TABLE])],
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::enum(LeadStatus::class)],
            'source' => ['nullable', Rule::enum(LeadSource::class)],
        ];
    }

    public function view(): string
    {
        return $this->input('view', self::BOARD);
    }

    public function isBoard(): bool
    {
        return $this->view() === self::BOARD;
    }

    /** @return array{search: ?string, status: ?LeadStatus, source: ?LeadSource} */
    public function filters(): array
    {
        return [
            'search' => $this->filled('search') ? $this->string('search')->trim()->toString() : null,
            'status' => $this->isBoard() ? null : LeadStatus::tryFrom((string) $this->input('status')),
            'source' => LeadSource::tryFrom((string) $this->input('source')),
        ];
    }

    /** @return array{view: string, search: ?string, status: ?string, source: ?string} */
    public function selected(): array
    {
        return [
            'view' => $this->view(),
            'search' => $this->input('search'),
            'status' => $this->input('status'),
            'source' => $this->input('source'),
        ];
    }
}
