<?php

use Illuminate\Support\Facades\Route;
use Modules\Content\Http\Controllers\AboutUsController;
use Modules\Content\Http\Controllers\Admin\BrokerController;
use Modules\Content\Http\Controllers\Admin\FaqController as AdminFaqController;
use Modules\Content\Http\Controllers\FaqController;
use Modules\Content\Http\Controllers\PrivacyController;
use Modules\Content\Http\Controllers\TermsOfServiceController;

Route::get('/about', AboutUsController::class)->name('about.index');
Route::get('/faqs', FaqController::class)->name('faqs.index');
Route::get('/terms-of-service', TermsOfServiceController::class)->name('terms.index');
Route::get('/privacy-policy', PrivacyController::class)->name('privacy.index');

// Created and edited in modals on their list pages, so no create/edit routes (ADMIN_UI.md §5)
Route::admin()->group(function () {
    $modal = ['index', 'store', 'update', 'destroy'];

    Route::resource('faqs', AdminFaqController::class)->only($modal);
    Route::resource('brokers', BrokerController::class)->only($modal);
});
