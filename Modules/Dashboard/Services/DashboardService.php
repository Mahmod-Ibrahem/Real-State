<?php

namespace Modules\Dashboard\Services;

use Illuminate\Support\Collection;
use Modules\Lead\Models\ContactLead;
use Modules\Lead\Models\PropertyLead;
use Modules\Location\Models\Area;
use Modules\Location\Models\City;
use Modules\Location\Models\Country;
use Modules\Property\Enums\ListingPurpose;
use Modules\Property\Enums\PropertyStatus;
use Modules\Property\Enums\ReviewStatus;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyType;

final class DashboardService
{
    private const RECENT = 5;

    public function listings(): array
    {
        $available = fn (ListingPurpose $purpose) => Property::where('purpose', $purpose)
            ->where('status', PropertyStatus::Available)->count();

        return [
            'total' => Property::count(),
            'published' => Property::active()->count(),
            'pending_review' => Property::where('review_status', ReviewStatus::Pending)->count(),
            'for_sale' => $available(ListingPurpose::Sale),
            'for_rent' => $available(ListingPurpose::Rent),
            'sold' => Property::where('status', PropertyStatus::Sold)->count(),
            'rented' => Property::where('status', PropertyStatus::Rented)->count(),
        ];
    }

    public function leadsThisMonth(): array
    {
        $thisMonth = now()->startOfMonth();

        return [
            'property_leads' => PropertyLead::where('created_at', '>=', $thisMonth)->count(),
            'contact_leads' => ContactLead::where('created_at', '>=', $thisMonth)->count(),
        ];
    }

    public function catalog(): array
    {
        return [
            'property_types' => PropertyType::count(),
            'countries' => Country::count(),
            'cities' => City::count(),
            'areas' => Area::count(),
        ];
    }

    public function recentPropertyLeads(): Collection
    {
        return PropertyLead::with('property:id,name')->latest()->take(self::RECENT)->get();
    }

    public function recentContactLeads(): Collection
    {
        return ContactLead::latest()->take(self::RECENT)->get(['id', 'full_name', 'subject', 'created_at']);
    }
}
