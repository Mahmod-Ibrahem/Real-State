<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\PropertyController;
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
    $modalResource = ['index', 'store', 'update', 'destroy'];

    Route::get('/dashboard', \App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');

    Route::resource('properties', PropertyController::class)->except('show');
    Route::resource('property-types', \App\Http\Controllers\Admin\PropertyTypeController::class)->names('propertyTypes')
        ->only($modalResource);
    Route::resource('property-classifications', \App\Http\Controllers\Admin\PropertyClassificationController::class)
        ->names('propertyClassifications')->only($modalResource);
    Route::resource('property-features', \App\Http\Controllers\Admin\PropertyFeatureController::class)
        ->names('propertyFeatures')->only($modalResource);
    Route::resource('feature-types', \App\Http\Controllers\Admin\FeatureTypeController::class)->only($modalResource);

    Route::resource('countries', \App\Http\Controllers\Admin\CountryController::class)->only($modalResource);
    Route::resource('cities', \App\Http\Controllers\Admin\CityController::class)->only($modalResource);
    Route::resource('areas', AreaController::class)->only($modalResource);

    Route::resource('property-leads', \App\Http\Controllers\Admin\PropertyLeadController::class)->names('propertyLeads')
        ->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('contact-leads', \App\Http\Controllers\Admin\ContactLeadController::class)->names('contactLeads')
        ->only(['index', 'edit', 'update', 'destroy']);

    Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class)->names('faq')->only($modalResource);
    Route::resource('content', \App\Http\Controllers\Admin\BasicContentController::class)->only(['index', 'edit', 'update']);
    Route::resource('brokers', \App\Http\Controllers\Admin\BrokerController::class)->names('broker')->except('show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

