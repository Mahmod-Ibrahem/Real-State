<?php

use Illuminate\Support\Facades\Route;
use Modules\Property\Http\Controllers\Admin\FeatureTypeController;
use Modules\Property\Http\Controllers\Admin\PropertyClassificationController;
use Modules\Property\Http\Controllers\Admin\PropertyController;
use Modules\Property\Http\Controllers\Admin\PropertyFeatureController;
use Modules\Property\Http\Controllers\Admin\PropertyListingStateController;
use Modules\Property\Http\Controllers\Admin\PropertyTypeController as AdminPropertyTypeController;
use Modules\Property\Http\Controllers\FavouriteListingController;
use Modules\Property\Http\Controllers\FeatureController;
use Modules\Property\Http\Controllers\ListingController;
use Modules\Property\Http\Controllers\PropertyTypeController;
use Modules\Property\Http\Controllers\SellController;

Route::resource('listing', ListingController::class)->only(['index', 'show']);
Route::get('/sell-buy-rent', [ListingController::class, 'sellBuyRent'])->name('sell-buy-rent');
Route::resource('sell', SellController::class)->only('index');
Route::get('/features', FeatureController::class)->name('features.index');
Route::get('/property/{PropertyType:slug}', [PropertyTypeController::class, 'show'])->name('property-types.show');
Route::resource('favourite-listing', FavouriteListingController::class)->only(['index', 'store', 'destroy']);

Route::admin()->group(function () {
    // Created and edited in modals on their list pages, so no create/edit routes (ADMIN_UI.md §5)
    $modal = ['index', 'store', 'update', 'destroy'];

    Route::resource('properties', PropertyController::class)->except('show');
    Route::patch('properties/{property}/listing', PropertyListingStateController::class)->name('properties.listing');
    Route::resource('property-types', AdminPropertyTypeController::class)->only($modal);
    Route::resource('property-classifications', PropertyClassificationController::class)->only($modal);
    Route::resource('property-features', PropertyFeatureController::class)->only($modal);
    Route::resource('feature-types', FeatureTypeController::class)->only($modal);
});
