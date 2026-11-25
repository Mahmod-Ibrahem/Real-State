<?php

namespace Modules\Lead\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Lead\Enums\LeadSource;
use Modules\Lead\Enums\LeadStatus;
use Modules\Lead\Events\PropertyLeadReceived;
use Modules\Lead\Exceptions\OwnerNotReachable;
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

    /** @param array{full_name: string, phone: string, email: string, message: ?string} $data */
    public function submit(string $slug, array $data): PropertyLead
    {
        return $this->record($this->properties->findListed($slug), LeadSource::Form, $data);
    }

    /** Saves a one-tap Call / WhatsApp lead and returns the owner's tel: or wa.me link. */
    public function contact(string $slug, LeadSource $source): string
    {
        $property = $this->properties->findListed($slug);
        $phone = ($source === LeadSource::Call ? $property->owner->phone : $property->owner->whatsapp_phone)
            ?? throw OwnerNotReachable::make();

        $this->record($property, $source);

        return $source === LeadSource::Call ? "tel:{$phone}" : $this->whatsAppLink($phone, $property);
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

    /** @param array{property_id: int, full_name: string, email: ?string, phone: ?string, message: ?string} $data */
    public function add(array $data): PropertyLead
    {
        $property = Property::query()->findOrFail($data['property_id']);

        return PropertyLead::create([
            ...$data,
            'owner_id' => $property->owner_id,
            'source' => LeadSource::Manual,
            'status' => LeadStatus::New,
        ]);
    }

    /**
     * The properties a lead can be added to by hand. A null owner means every property (admins).
     *
     * @return Collection<int, Property>
     */
    public function propertyOptions(?User $owner): Collection
    {
        return Property::query()
            ->when($owner, fn (Builder $query) => $query->whereBelongsTo($owner, 'owner'))
            ->latest('id')
            ->get(['id', 'name']);
    }

    /** @param array{full_name: ?string, email: ?string, phone: ?string} $contact */
    public function updateContact(PropertyLead $lead, array $contact): void
    {
        $lead->update($contact);
    }

    public function updateStatus(PropertyLead $lead, LeadStatus $status): void
    {
        $lead->update(['status' => $status]);
    }

    public function delete(PropertyLead $lead): void
    {
        $lead->delete();
    }

    /** @param array{full_name?: string, phone?: string, email?: string, message?: ?string} $contact */
    private function record(Property $property, LeadSource $source, array $contact = []): PropertyLead
    {
        $lead = PropertyLead::create([
            ...$contact,
            'property_id' => $property->id,
            'owner_id' => $property->owner_id,
            'source' => $source,
            'status' => LeadStatus::New,
        ]);

        PropertyLeadReceived::dispatch($lead);

        return $lead;
    }

    private function whatsAppLink(string $phone, Property $property): string
    {
        $text = __('Hello, I am interested in ":property" on Qeima: :url', [
            'property' => $property->name,
            'url' => route('listing.show', $property->slug),
        ]);

        return 'https://wa.me/' . ltrim($phone, '+') . '?text=' . rawurlencode($text);
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
