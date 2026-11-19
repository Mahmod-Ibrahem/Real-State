<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyLeadController extends Controller
{
    public function index()
    {
        $propertyLeads=\App\Models\PropertyLead::paginate(10);
        return Inertia::render('Admin/PropertyLeads/Index', ['propertyLeads' => $propertyLeads]);
    }

    public function edit($propertyLead)
    {
        $propertyLead=\App\Models\PropertyLead::find($propertyLead);
        return Inertia::render('Admin/PropertyLeads/Edit', ['propertyLead' => $propertyLead]);
    }

    public function update($propertyLead, Request $request)
    {
        $propertyLead=\App\Models\PropertyLead::find($propertyLead);
        $propertyLead->update($request->all());
        return redirect()->route('propertyLeads.index');
    }

    public function destroy($propertyLead)
    {
        $propertyLead=\App\Models\PropertyLead::find($propertyLead);
        $propertyLead->delete();
        return redirect()->route('propertyLeads.index');
    }
}
