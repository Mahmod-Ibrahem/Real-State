<?php

use Illuminate\Support\Facades\Route;
use Modules\Lead\Http\Controllers\Admin\ContactLeadController;
use Modules\Lead\Http\Controllers\Admin\PropertyLeadController;
use Modules\Lead\Http\Controllers\ClientInquiryController;
use Modules\Lead\Http\Controllers\ContactController;

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/send', [ContactController::class, 'send'])->name('contact.post');
Route::post('/client-inquiry', [ClientInquiryController::class, 'ClientInquiry'])->name('client-inquiries.store');

Route::admin()->group(function () {
    Route::resource('property-leads', PropertyLeadController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('contact-leads', ContactLeadController::class)->only(['index', 'edit', 'update', 'destroy']);
});
