<?php

namespace Modules\Lead\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Modules\Lead\Enums\LeadSource;
use Modules\Lead\Enums\LeadStatus;
use Modules\Lead\Events\PropertyLeadReceived;
use Modules\Lead\Models\PropertyLead;
use Modules\Property\Models\Property;
use Modules\Property\Services\PropertyDetailService;

final class PropertyLeadService
{
    private const PER_PAGE = 15;
    private const BOARD_COLUMN_LIMIT = 50;
    private const LIST_RELATIONS = ['property:id,name', 'owner:id,name'];

    public function __construct(private readonly PropertyDetailService $properties)
    {
    }

    /** @param array{full_name: string, phone: string, email: ?string, message: ?string} $data */
    public function submit(string $slug, array $data, LeadSource $source): PropertyLead
    {
        $property = $this->properties->findListed($slug);

        $lead = PropertyLead::create([
            ...$data,
            'property_id' => $property->id,
            'owner_id' => $property->owner_id,
            'source' => $source,
            'status' => LeadStatus::New,
        ]);

        PropertyLeadReceived::dispatch($lead);

        return $lead;
    }

    public function contactLinkFor(PropertyLead $lead): ?string
    {
        $phone = $lead->owner->phone;

        if ($phone === null) {
            return null;
        }

        return match ($lead->source) {
            LeadSource::Form => null,
            LeadSource::Call => "tel:{$phone}",
            LeadSource::WhatsApp => 'https://wa.me/' . ltrim($phone, '+') . '?text=' . rawurlencode(
                __('Hello, I am interested in ":property" on Qeima.', ['property' => $lead->property->name])
            ),
        };
    }

    /** @param array{search: ?string, status: ?LeadStatus, source: ?LeadSource} $filters */
    public function paginate(?User $owner, array $filters): LengthAwarePaginator
    {
        return $this->filtered($owner, $filters)
            ->with(self::LIST_RELATIONS)
            ->latest()
            ->paginate(self::PER_PAGE)
            ->withQueryString();
    }

    /**
     * One column per status, newest first, capped so a long column stays light.
     *
     * @param array{search: ?string, status: ?LeadStatus, source: ?LeadSource} $filters
     * @return list<array{status: LeadStatus, count: int, leads: \Illuminate\Support\Collection<int, PropertyLead>}>
     */
    public function board(?User $owner, array $filters): array
    {
        return array_map(fn (LeadStatus $status) => [
            'status' => $status,
            'count' => $this->filtered($owner, $filters)->where('status', $status)->count(),
            'leads' => $this->filtered($owner, $filters)->where('status', $status)
                ->with(self::LIST_RELATIONS)
                ->latest()
                ->limit(self::BOARD_COLUMN_LIMIT)
                ->get(),
        ], LeadStatus::cases());
    }

    public function newCountFor(User $owner): int
    {
        return PropertyLead::query()->whereBelongsTo($owner, 'owner')->where('status', LeadStatus::New)->count();
    }

    public function existsFor(Property $property): bool
    {
        return PropertyLead::query()->whereBelongsTo($property)->exists();
    }

    public function updateStatus(PropertyLead $lead, LeadStatus $status): void
    {
        $lead->update(['status' => $status]);
    }

    public function delete(PropertyLead $lead): void
    {
        $lead->delete();
    }

    /**
     * A null owner means every lead (admins).
     *
     * @param array{search: ?string, status: ?LeadStatus, source: ?LeadSource} $filters
     */
    private function filtered(?User $owner, array $filters): Builder
    {
        ['search' => $search, 'status' => $status, 'source' => $source] = $filters;

        return PropertyLead::query()
            ->when($owner, fn (Builder $query) => $query->whereBelongsTo($owner, 'owner'))
            ->when($status, fn (Builder $query) => $query->where('status', $status))
            ->when($source, fn (Builder $query) => $query->where('source', $source))
            ->when($search, fn (Builder $query) => $query->where(fn (Builder $query) => $query
                ->where('full_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")));
    }
}
