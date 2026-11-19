<?php

namespace Modules\Lead\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyLeadController extends Controller
{
    public function index()
    {
        $propertyLeads = \Modules\Lead\Models\PropertyLead::with('property')->latest()->paginate(10)
            // Tables refer to records by name, never by id (ADMIN_UI.md §5)
            ->through(fn ($lead) => [...$lead->withoutRelations()->toArray(), 'property_name' => $lead->property?->name]);

        return Inertia::render('Admin/PropertyLeads/Index', ['propertyLeads' => $propertyLeads]);
    }

    public function edit($propertyLead)
    {
        $propertyLead = \Modules\Lead\Models\PropertyLead::findOrFail($propertyLead);

        return Inertia::render('Admin/PropertyLeads/Edit', [
            'propertyLead' => $propertyLead,
            // Picked by title, never typed as an id (ADMIN_UI.md §5)
            'properties' => \Modules\Property\Models\Property::orderBy('id')->get(['id', 'name'])
                ->map(fn ($property) => ['id' => $property->id, 'name' => $property->name]),
        ]);
    }

    public function update($propertyLead, Request $request)
    {
        $propertyLead=\Modules\Lead\Models\PropertyLead::find($propertyLead);
        $propertyLead->update($request->all());
        return redirect()->route('admin.property-leads.index');
    }

    public function destroy($propertyLead)
    {
        $propertyLead=\Modules\Lead\Models\PropertyLead::find($propertyLead);
        $propertyLead->delete();
        return redirect()->route('admin.property-leads.index');
    }
}
