<?php

namespace Modules\Dashboard\Http\Controllers\Admin;

use Modules\Property\Enums\ListingPurpose;
use Modules\Property\Enums\PropertyStatus;
use App\Http\Controllers\Controller;
use Modules\Location\Models\Area;
use Modules\Location\Models\City;
use Modules\Lead\Models\ContactLead;
use Modules\Location\Models\Country;
use Modules\Property\Models\Property;
use Modules\Lead\Models\PropertyLead;
use Modules\Property\Models\PropertyType;
use Inertia\Inertia;

class DashboardController extends Controller
{
    private const RECENT = 5;

    public function __invoke()
    {
        return Inertia::render('Admin/Dashboard', [
            'listings' => $this->listings(),
            'leads' => $this->leads(),
            'catalog' => $this->catalog(),
            'recentPropertyLeads' => $this->recentPropertyLeads(),
            'recentContactLeads' => ContactLead::latest()->take(self::RECENT)->get(['id', 'full_name', 'subject', 'created_at']),
        ]);
    }

    private function listings(): array
    {
        $available = fn (ListingPurpose $purpose) => Property::where('purpose', $purpose)
            ->where('status', PropertyStatus::Available)->count();

        return [
            'total' => Property::count(),
            'published' => Property::active()->count(),
            'forSale' => $available(ListingPurpose::Sale),
            'forRent' => $available(ListingPurpose::Rent),
            'sold' => Property::where('status', PropertyStatus::Sold)->count(),
            'rented' => Property::where('status', PropertyStatus::Rented)->count(),
        ];
    }

    private function leads(): array
    {
        $thisMonth = now()->startOfMonth();

        return [
            'propertyLeads' => PropertyLead::where('created_at', '>=', $thisMonth)->count(),
            'contactLeads' => ContactLead::where('created_at', '>=', $thisMonth)->count(),
        ];
    }

    private function catalog(): array
    {
        return [
            'propertyTypes' => PropertyType::count(),
            'countries' => Country::count(),
            'cities' => City::count(),
            'areas' => Area::count(),
        ];
    }

    private function recentPropertyLeads()
    {
        return PropertyLead::with('property:id,name')->latest()->take(self::RECENT)->get()
            ->map(fn (PropertyLead $lead) => [
                'id' => $lead->id,
                'full_name' => $lead->full_name,
                'property' => $lead->property?->name,
                'created_at' => $lead->created_at,
            ]);
    }
}
