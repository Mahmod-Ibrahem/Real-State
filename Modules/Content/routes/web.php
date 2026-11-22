<?php

use Illuminate\Support\Facades\Route;
use Modules\Content\Enums\ContentPermission;
use Modules\Content\Http\Controllers\AboutUsController;
use Modules\Content\Http\Controllers\Admin\BrokerController;
use Modules\Content\Http\Controllers\Admin\FaqController;
use Modules\Content\Http\Controllers\PrivacyController;
use Modules\Content\Http\Controllers\TermsOfServiceController;

Route::get('/about', AboutUsController::class)->name('about.index');
Route::get('/terms-of-service', TermsOfServiceController::class)->name('terms.index');
Route::get('/privacy-policy', PrivacyController::class)->name('privacy.index');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('faqs', [FaqController::class, 'index'])->name('faqs.index')
        ->middleware(['auth', ContentPermission::ViewFaqs->middleware()]);
    Route::post('faqs', [FaqController::class, 'store'])->name('faqs.store')
        ->middleware(['auth', ContentPermission::CreateFaqs->middleware()]);
    Route::match(['put', 'patch'], 'faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update')
        ->middleware(['auth', ContentPermission::EditFaqs->middleware()]);
    Route::delete('faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy')
        ->middleware(['auth', ContentPermission::DeleteFaqs->middleware()]);

    Route::get('brokers', [BrokerController::class, 'index'])->name('brokers.index')
        ->middleware(['auth', ContentPermission::ViewBrokers->middleware()]);
    Route::post('brokers', [BrokerController::class, 'store'])->name('brokers.store')
        ->middleware(['auth', ContentPermission::CreateBrokers->middleware()]);
    Route::match(['put', 'patch'], 'brokers/{broker}', [BrokerController::class, 'update'])->name('brokers.update')
        ->middleware(['auth', ContentPermission::EditBrokers->middleware()]);
    Route::delete('brokers/{broker}', [BrokerController::class, 'destroy'])->name('brokers.destroy')
        ->middleware(['auth', ContentPermission::DeleteBrokers->middleware()]);
});
