<?php

use Illuminate\Support\Facades\Route;
use Modules\Property\Enums\PropertyPermission;
use Modules\Property\Http\Controllers\Admin\ApprovePropertyController;
use Modules\Property\Http\Controllers\Admin\FeatureTypeController;
use Modules\Property\Http\Controllers\Admin\PropertyClassificationController;
use Modules\Property\Http\Controllers\Admin\PropertyController;
use Modules\Property\Http\Controllers\Admin\PropertyFeatureController;
use Modules\Property\Http\Controllers\Admin\PropertyListingStateController;
use Modules\Property\Http\Controllers\Admin\PropertyTypeController as AdminPropertyTypeController;
use Modules\Property\Http\Controllers\Admin\RejectPropertyController;
use Modules\Property\Http\Controllers\FavouriteListingController;
use Modules\Property\Http\Controllers\FeatureController;
use Modules\Property\Http\Controllers\ListingController;
use Modules\Property\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use Modules\Property\Http\Controllers\Owner\PropertyController as OwnerPropertyController;
use Modules\Property\Http\Controllers\Owner\PublishPropertyController;
use Modules\Property\Http\Controllers\Owner\UnpublishPropertyController;
use Modules\Property\Http\Controllers\PropertyTypeController;
use Modules\Property\Http\Controllers\SellController;

Route::resource('listing', ListingController::class)->only(['index', 'show']);
Route::get('/sell-buy-rent', [ListingController::class, 'sellBuyRent'])->name('sell-buy-rent');
Route::resource('sell', SellController::class)->only('index');
Route::get('/features', FeatureController::class)->name('features.index');
Route::get('/property/{PropertyType:slug}', [PropertyTypeController::class, 'show'])->name('property-types.show');
Route::resource('favourite-listing', FavouriteListingController::class)->only(['index', 'store', 'destroy']);

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('properties', [PropertyController::class, 'index'])->name('properties.index')
        ->middleware(['auth', PropertyPermission::View->middleware()]);
    Route::get('properties/create', [PropertyController::class, 'create'])->name('properties.create')
        ->middleware(['auth', PropertyPermission::Create->middleware()]);
    Route::post('properties', [PropertyController::class, 'store'])->name('properties.store')
        ->middleware(['auth', PropertyPermission::Create->middleware()]);
    Route::get('properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit')
        ->middleware(['auth', PropertyPermission::Edit->middleware()]);
    Route::match(['put', 'patch'], 'properties/{property}', [PropertyController::class, 'update'])->name('properties.update')
        ->middleware(['auth', PropertyPermission::Edit->middleware()]);
    Route::delete('properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy')
        ->middleware(['auth', PropertyPermission::Delete->middleware()]);
    Route::patch('properties/{property}/listing', PropertyListingStateController::class)->name('properties.listing')
        ->middleware(['auth', PropertyPermission::Edit->middleware()]);
    Route::patch('properties/{property}/approve', ApprovePropertyController::class)->name('properties.approve')
        ->middleware(['auth', PropertyPermission::Review->middleware()]);
    Route::patch('properties/{property}/reject', RejectPropertyController::class)->name('properties.reject')
        ->middleware(['auth', PropertyPermission::Review->middleware()]);

    Route::get('property-types', [AdminPropertyTypeController::class, 'index'])->name('property-types.index')
        ->middleware(['auth', PropertyPermission::ViewPropertyTypes->middleware()]);
    Route::post('property-types', [AdminPropertyTypeController::class, 'store'])->name('property-types.store')
        ->middleware(['auth', PropertyPermission::CreatePropertyTypes->middleware()]);
    Route::match(['put', 'patch'], 'property-types/{property_type}', [AdminPropertyTypeController::class, 'update'])->name('property-types.update')
        ->middleware(['auth', PropertyPermission::EditPropertyTypes->middleware()]);
    Route::delete('property-types/{property_type}', [AdminPropertyTypeController::class, 'destroy'])->name('property-types.destroy')
        ->middleware(['auth', PropertyPermission::DeletePropertyTypes->middleware()]);

    Route::get('property-classifications', [PropertyClassificationController::class, 'index'])->name('property-classifications.index')
        ->middleware(['auth', PropertyPermission::ViewClassifications->middleware()]);
    Route::post('property-classifications', [PropertyClassificationController::class, 'store'])->name('property-classifications.store')
        ->middleware(['auth', PropertyPermission::CreateClassifications->middleware()]);
    Route::match(['put', 'patch'], 'property-classifications/{property_classification}', [PropertyClassificationController::class, 'update'])->name('property-classifications.update')
        ->middleware(['auth', PropertyPermission::EditClassifications->middleware()]);
    Route::delete('property-classifications/{property_classification}', [PropertyClassificationController::class, 'destroy'])->name('property-classifications.destroy')
        ->middleware(['auth', PropertyPermission::DeleteClassifications->middleware()]);

    Route::get('property-features', [PropertyFeatureController::class, 'index'])->name('property-features.index')
        ->middleware(['auth', PropertyPermission::ViewFeatures->middleware()]);
    Route::post('property-features', [PropertyFeatureController::class, 'store'])->name('property-features.store')
        ->middleware(['auth', PropertyPermission::CreateFeatures->middleware()]);
    Route::match(['put', 'patch'], 'property-features/{property_feature}', [PropertyFeatureController::class, 'update'])->name('property-features.update')
        ->middleware(['auth', PropertyPermission::EditFeatures->middleware()]);
    Route::delete('property-features/{property_feature}', [PropertyFeatureController::class, 'destroy'])->name('property-features.destroy')
        ->middleware(['auth', PropertyPermission::DeleteFeatures->middleware()]);

    Route::get('feature-types', [FeatureTypeController::class, 'index'])->name('feature-types.index')
        ->middleware(['auth', PropertyPermission::ViewFeatureTypes->middleware()]);
    Route::post('feature-types', [FeatureTypeController::class, 'store'])->name('feature-types.store')
        ->middleware(['auth', PropertyPermission::CreateFeatureTypes->middleware()]);
    Route::match(['put', 'patch'], 'feature-types/{feature_type}', [FeatureTypeController::class, 'update'])->name('feature-types.update')
        ->middleware(['auth', PropertyPermission::EditFeatureTypes->middleware()]);
    Route::delete('feature-types/{feature_type}', [FeatureTypeController::class, 'destroy'])->name('feature-types.destroy')
        ->middleware(['auth', PropertyPermission::DeleteFeatureTypes->middleware()]);
});

Route::prefix('supplier')->name('supplier.')->group(function () {
    Route::get('dashboard', OwnerDashboardController::class)->name('dashboard')
        ->middleware(['auth', PropertyPermission::ViewOwn->middleware()]);
    Route::get('properties', [OwnerPropertyController::class, 'index'])->name('properties.index')
        ->middleware(['auth', PropertyPermission::ViewOwn->middleware()]);
    Route::get('properties/create', [OwnerPropertyController::class, 'create'])->name('properties.create')
        ->middleware(['auth', PropertyPermission::CreateOwn->middleware()]);
    Route::post('properties', [OwnerPropertyController::class, 'store'])->name('properties.store')
        ->middleware(['auth', PropertyPermission::CreateOwn->middleware()]);
    Route::get('properties/{property}/edit', [OwnerPropertyController::class, 'edit'])->name('properties.edit')
        ->middleware(['auth', PropertyPermission::EditOwn->middleware(), 'can:update,property']);
    Route::match(['put', 'patch'], 'properties/{property}', [OwnerPropertyController::class, 'update'])->name('properties.update')
        ->middleware(['auth', PropertyPermission::EditOwn->middleware(), 'can:update,property']);
    Route::patch('properties/{property}/publish', PublishPropertyController::class)->name('properties.publish')
        ->middleware(['auth', PropertyPermission::PublishOwn->middleware(), 'can:publish,property']);
    Route::patch('properties/{property}/unpublish', UnpublishPropertyController::class)->name('properties.unpublish')
        ->middleware(['auth', PropertyPermission::PublishOwn->middleware(), 'can:publish,property']);
});
