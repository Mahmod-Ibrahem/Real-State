<?php

use Illuminate\Support\Facades\Route;
use Modules\Lead\Enums\LeadPermission;
use Modules\Lead\Http\Controllers\Admin\ContactLeadController;
use Modules\Lead\Http\Controllers\Admin\PropertyLeadController;
use Modules\Lead\Http\Controllers\ClientInquiryController;
use Modules\Lead\Http\Controllers\ContactController;

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/send', [ContactController::class, 'send'])->name('contact.post');
Route::post('/client-inquiry', [ClientInquiryController::class, 'ClientInquiry'])->name('client-inquiries.store');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('property-leads', [PropertyLeadController::class, 'index'])->name('property-leads.index')
        ->middleware(['auth', LeadPermission::ViewPropertyLeads->middleware()]);
    Route::get('property-leads/{property_lead}/edit', [PropertyLeadController::class, 'edit'])->name('property-leads.edit')
        ->middleware(['auth', LeadPermission::ViewPropertyLeads->middleware()]);
    Route::match(['put', 'patch'], 'property-leads/{property_lead}', [PropertyLeadController::class, 'update'])->name('property-leads.update')
        ->middleware(['auth', LeadPermission::EditPropertyLeads->middleware()]);
    Route::delete('property-leads/{property_lead}', [PropertyLeadController::class, 'destroy'])->name('property-leads.destroy')
        ->middleware(['auth', LeadPermission::DeletePropertyLeads->middleware()]);

    Route::get('contact-leads', [ContactLeadController::class, 'index'])->name('contact-leads.index')
        ->middleware(['auth', LeadPermission::ViewContactLeads->middleware()]);
    Route::get('contact-leads/{contact_lead}/edit', [ContactLeadController::class, 'edit'])->name('contact-leads.edit')
        ->middleware(['auth', LeadPermission::ViewContactLeads->middleware()]);
    Route::match(['put', 'patch'], 'contact-leads/{contact_lead}', [ContactLeadController::class, 'update'])->name('contact-leads.update')
        ->middleware(['auth', LeadPermission::EditContactLeads->middleware()]);
    Route::delete('contact-leads/{contact_lead}', [ContactLeadController::class, 'destroy'])->name('contact-leads.destroy')
        ->middleware(['auth', LeadPermission::DeleteContactLeads->middleware()]);
});
