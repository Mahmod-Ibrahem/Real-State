<?php

use Illuminate\Support\Facades\Route;
use Modules\Lead\Enums\LeadPermission;
use Modules\Lead\Http\Controllers\Admin\ContactLeadController;
use Modules\Lead\Http\Controllers\Admin\PropertyLeadController as AdminPropertyLeadController;
use Modules\Lead\Http\Controllers\Admin\PropertyLeadStatusController as AdminPropertyLeadStatusController;
use Modules\Lead\Http\Controllers\ContactController;
use Modules\Lead\Http\Controllers\Owner\PropertyLeadController as OwnerPropertyLeadController;
use Modules\Lead\Http\Controllers\Owner\PropertyLeadStatusController as OwnerPropertyLeadStatusController;
use Modules\Lead\Http\Controllers\PropertyLeadController;

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/send', [ContactController::class, 'send'])->name('contact.post');
Route::post('listing/{listing}/leads', [PropertyLeadController::class, 'store'])->name('listing.leads.store')
    ->middleware(['throttle:5,1']);

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('property-leads', [AdminPropertyLeadController::class, 'index'])->name('property-leads.index')
        ->middleware(['auth', LeadPermission::ViewPropertyLeads->middleware()]);
    Route::patch('property-leads/{property_lead}/status', AdminPropertyLeadStatusController::class)->name('property-leads.status')
        ->middleware(['auth', LeadPermission::EditPropertyLeads->middleware()]);
    Route::delete('property-leads/{property_lead}', [AdminPropertyLeadController::class, 'destroy'])->name('property-leads.destroy')
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

Route::prefix('supplier')->name('supplier.')->group(function () {
    Route::get('leads', [OwnerPropertyLeadController::class, 'index'])->name('leads.index')
        ->middleware(['auth', LeadPermission::ViewOwnPropertyLeads->middleware()]);
    Route::patch('leads/{property_lead}/status', OwnerPropertyLeadStatusController::class)->name('leads.status')
        ->middleware(['auth', LeadPermission::EditOwnPropertyLeads->middleware(), 'can:update,property_lead']);
});
