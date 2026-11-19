<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ListingPurpose;
use App\Enums\PropertyStatus;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\ContactLead;
use App\Models\Country;
use App\Models\Property;
use App\Models\PropertyLead;
use App\Models\PropertyType;
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
