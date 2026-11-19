<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactLeadController extends Controller
{
    public function index()
    {
        $contactLeads = \App\Models\ContactLead::orderBy('created_at', 'desc')->paginate(10);
        return Inertia::render('Admin/ContactLeads/Index', ['contactLeads' => $contactLeads]);
    }


    public function edit($contactLead)
    {
        $contactLead = \App\Models\ContactLead::find($contactLead);
        return Inertia::render('Admin/ContactLeads/Edit', ['contactLead' => $contactLead]);
    }

    public function update($contactLead, Request $request)
    {
        $contactLead = \App\Models\ContactLead::find($contactLead);
        $contactLead->update($request->all());
        return redirect()->route('contactLeads.index');
    }

    public function destroy($contactLead)
    {
        $contactLead = \App\Models\ContactLead::find($contactLead);
        $contactLead->delete();
        return redirect()->route('contactLeads.index');
    }
}
