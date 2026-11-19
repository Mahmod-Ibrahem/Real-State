<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyImagesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::post('/change-locale', [\App\Http\Controllers\LanguageController::class, 'changeLocale'])->name('change.locale');
Route::post('/send', [ContactController::class, 'send'])->name('contact.post');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', \App\Http\Controllers\AboutUsController::class)->name('about.index');
Route::get('/features', \App\Http\Controllers\FeatureController::class)->name('features.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::get('/faqs', \App\Http\Controllers\FaqController::class)->name('faqs.index');
Route::resource('sell', \App\Http\Controllers\SellController::class);
Route::resource('listing', \App\Http\Controllers\ListingController::class);
route::get('/terms-of-service', \App\Http\Controllers\TermsOfServiceController::class)->name('terms.index');
route::get('/privacy-policy', \App\Http\Controllers\PrivacyController::class)->name('privacy.index');
Route::get('/sell-buy-rent', [\App\Http\Controllers\ListingController::class, 'sellBuyRent'])->name('sell.buy.rent');
Route::get('/property/{PropertyType:slug}', [\App\Http\Controllers\propertyTypeController::class, 'show'])->name('propertyType.show');
Route::post('/client-inquiry', [\App\Http\Controllers\ClientInquiryController::class, 'ClientInquiry'])->name('ClientInquiry');
Route::resource('/favourite-listing', \App\Http\Controllers\Admin\FavouriteListingController::class);
Route::fallback(function () {
    return Inertia::render('404');
});
Route::middleware(['auth', 'role:Super Admin'])->prefix('admin')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    // Admin has no detail pages: lists link straight to edit, so every resource skips `show`
    Route::resource('areas', AreaController::class)->except('show');
    Route::resource('countries', \App\Http\Controllers\Admin\CountryController::class)->except('show');
    Route::resource('cities', \App\Http\Controllers\Admin\CityController::class)->except('show');
    Route::resource('properties', PropertyController::class)->except('show');
    Route::resource('propertyImages', PropertyImagesController::class)->only(['index', 'store', 'destroy']);
    Route::resource('propertyTypes', \App\Http\Controllers\Admin\PropertyTypeController::class)->except('show');
    Route::resource('propertyFeatures', \App\Http\Controllers\Admin\PropertyFeatureController::class)->except('show');
    Route::resource('propertyClassifications', \App\Http\Controllers\Admin\PropertyClassificationController::class)->except('show');
    route::get('/dashboard', \App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/faq', \App\Http\Controllers\Admin\FaqController::class)->except('show');
    Route::resource('/content', \App\Http\Controllers\Admin\BasicContentController::class)->only(['index', 'edit', 'update']);
    Route::resource('/propertyLeads', \App\Http\Controllers\Admin\PropertyLeadController::class)->except('show');
    Route::resource('/contactLeads', \App\Http\Controllers\Admin\ContactLeadController::class)->except('show');
    Route::resource('/broker', \App\Http\Controllers\Admin\BrokerController::class)->except('show');
    Route::resource('feature-types', \App\Http\Controllers\Admin\FeatureTypeController::class)->except('show');
});

require __DIR__ . '/auth.php';

